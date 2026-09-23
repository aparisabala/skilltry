<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\AdminUserRole;
use App\Models\LibCategory;
use App\Models\LibSubcategory;
use App\Models\LibSpecialization;
use App\Http\Middleware\SetBootConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(SetBootConfig::class);
        if (!defined('V')) {
            define('V', 'test');
        }
    }

    private function admin(): AdminUser
    {
        $role = AdminUserRole::firstOrCreate(['code' => 'SA'], ['name' => 'SA']);
        $user = new AdminUser;
        $user->forceFill([
            'uuid' => (string) Str::uuid(), 'name' => 'HR Administrator',
            'email' => Str::uuid().'@example.test', 'password' => Hash::make('test-password-123'),
            'admin_user_role_id' => $role->id, 'setup_done' => 'yes',
            'admin_type' => 'system_user', 'status' => 'Active',
        ])->save();
        return $user;
    }

    private function token(AdminUser $user): string
    {
        return $user->createToken('tests', ['*'], now()->addHour())->plainTextToken;
    }

    public function test_category_subcategory_specialization_hierarchy_and_scoped_lists(): void
    {
        $this->withToken($this->token($this->admin()));

        $this->postJson('/api/v1/admin/category', ['name' => 'Engineering'])
            ->assertOk()->assertJsonPath('success', true);
        $category = LibCategory::where('name', 'Engineering')->firstOrFail();

        // A second, unrelated category + subcategory to prove scoping actually filters.
        $otherCategory = LibCategory::create(['name' => 'Medicine']);
        $otherSubcategoryBase = '/api/v1/admin/category/'.$otherCategory->id.'/subcategory';
        $this->postJson($otherSubcategoryBase, ['name' => 'Cardiology'])->assertOk()->assertJsonPath('success', true);

        $subcategoryBase = '/api/v1/admin/category/'.$category->id.'/subcategory';
        $this->postJson($subcategoryBase, ['name' => 'Software'])
            ->assertOk()->assertJsonPath('success', true);
        $subcategory = LibSubcategory::where('name', 'Software')->firstOrFail();
        $this->assertSame($category->id, $subcategory->lib_category_id);

        $this->postJson($subcategoryBase.'/list')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Software'])
            ->assertJsonMissing(['name' => 'Cardiology']);

        $specializationBase = '/api/v1/admin/category/'.$category->id.'/subcategory/'.$subcategory->id.'/specialization';
        $this->postJson($specializationBase, ['name' => 'Backend'])
            ->assertOk()->assertJsonPath('success', true);
        $specialization = LibSpecialization::where('name', 'Backend')->firstOrFail();
        $this->assertSame($category->id, $specialization->lib_category_id);
        $this->assertSame($subcategory->id, $specialization->lib_subcategory_id);

        $this->postJson($specializationBase.'/list')
            ->assertOk()->assertJsonFragment(['name' => 'Backend']);

        // Same subcategory name is allowed under a different category (composite-unique, not global-unique).
        $this->postJson($otherSubcategoryBase, ['name' => 'Software'])
            ->assertOk()->assertJsonPath('success', true);

        // Duplicate subcategory name within the SAME category is rejected.
        $this->postJson($subcategoryBase, ['name' => 'Software'])
            ->assertOk()->assertJsonPath('success', false);

        // Duplicate specialization name within the SAME subcategory is rejected.
        $this->postJson($specializationBase, ['name' => 'Backend'])
            ->assertOk()->assertJsonPath('success', false);
    }

    public function test_delete_guard_blocks_category_with_subcategories_and_subcategory_with_specializations(): void
    {
        $this->withToken($this->token($this->admin()));

        $category = LibCategory::create(['name' => 'Engineering']);
        $subcategoryBase = '/api/v1/admin/category/'.$category->id.'/subcategory';
        $this->postJson($subcategoryBase, ['name' => 'Software'])->assertOk()->assertJsonPath('success', true);
        $subcategory = LibSubcategory::where('name', 'Software')->firstOrFail();

        // Category still has a subcategory -> bulk delete of the category must be rejected.
        $this->postJson('/api/v1/admin/category/delete-list', ['ids' => [$category->id]])
            ->assertOk()->assertJsonPath('success', false)->assertJsonPath('bigError', true);
        $this->assertDatabaseHas('lib_categories', ['id' => $category->id]);

        $specializationBase = $subcategoryBase.'/'.$subcategory->id.'/specialization';
        $this->postJson($specializationBase, ['name' => 'Backend'])->assertOk()->assertJsonPath('success', true);

        // Subcategory still has a specialization -> bulk delete of the subcategory must be rejected.
        $this->postJson($subcategoryBase.'/delete-list', ['ids' => [$subcategory->id]])
            ->assertOk()->assertJsonPath('success', false)->assertJsonPath('bigError', true);
        $this->assertDatabaseHas('lib_subcategories', ['id' => $subcategory->id]);

        // With the specialization gone, the subcategory (and then the category) can be deleted.
        $specialization = LibSpecialization::where('name', 'Backend')->firstOrFail();
        $this->postJson($specializationBase.'/delete-list', ['ids' => [$specialization->id]])
            ->assertOk()->assertJsonPath('success', true);
        $this->postJson($subcategoryBase.'/delete-list', ['ids' => [$subcategory->id]])
            ->assertOk()->assertJsonPath('success', true);
        $this->postJson('/api/v1/admin/category/delete-list', ['ids' => [$category->id]])
            ->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseMissing('lib_categories', ['id' => $category->id]);
    }

    public function test_category_image_upload_on_store(): void
    {
        $admin = $this->admin();
        // Exercise real image processing in an isolated temporary directory.
        $working = getcwd();
        $temporary = storage_path('framework/testing/category-'.Str::uuid());
        mkdir($temporary.'/uploads/app/test/dyn/images', 0777, true);
        config(['i.service_domain' => 'test']);
        chdir($temporary);
        try {
            $this->withToken($this->token($admin))->post('/api/v1/admin/category', [
                'name' => 'Engineering',
                'image' => \Illuminate\Http\UploadedFile::fake()->image('icon.jpg'),
            ])->assertOk()->assertJsonPath('success', true);
            $category = LibCategory::where('name', 'Engineering')->firstOrFail();
            $this->assertNotEmpty($category->image);
            $this->assertFileExists(imagePaths()['dyn_image'].$category->image.'_300X300.jpg');
        } finally {
            chdir($working);
            \Illuminate\Support\Facades\File::deleteDirectory($temporary);
        }
    }

    public function test_category_routes_require_admin_authentication(): void
    {
        $this->getJson('/api/v1/admin/category')->assertUnauthorized()->assertJsonPath('success', false);
        $category = LibCategory::create(['name' => 'Engineering']);
        $this->postJson('/api/v1/admin/category/'.$category->id.'/subcategory', ['name' => 'Software'])
            ->assertUnauthorized();
    }

    public function test_category_web_pages_render_for_an_authenticated_admin_at_all_nesting_levels(): void
    {
        $this->actingAs($this->admin(), 'admin');
        $category = LibCategory::create(['name' => 'Engineering']);
        $subcategory = LibSubcategory::create(['name' => 'Software', 'lib_category_id' => $category->id]);

        foreach ([
            'category',
            'category/'.$category->id.'/subcategory',
            'category/'.$category->id.'/subcategory/'.$subcategory->id.'/specialization',
        ] as $path) {
            $response = $this->get('/admin/'.$path)->assertOk();
            $this->assertStringContainsString('<!DOCTYPE html>', $response->getContent());
        }
    }
}
