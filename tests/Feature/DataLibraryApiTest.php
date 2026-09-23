<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\AdminUserRole;
use App\Models\LibBank;
use App\Models\LibDivision;
use App\Models\LibDistrict;
use App\Http\Middleware\SetBootConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class DataLibraryApiTest extends TestCase
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

    public function test_flat_lookup_full_crud_lifecycle_for_bank(): void
    {
        $this->withToken($this->token($this->admin()));
        $base = '/api/v1/admin/datalibrary/bank';

        $this->postJson($base, ['name' => 'Sonali Bank'])->assertOk()->assertJsonPath('success', true);
        $bank = LibBank::where('name', 'Sonali Bank')->firstOrFail();

        $this->postJson($base.'/list')->assertOk()->assertJsonFragment(['name' => 'Sonali Bank']);

        $this->putJson($base.'/'.$bank->id, ['name' => 'Sonali Bank Ltd'])
            ->assertOk()->assertJsonPath('success', true);
        $this->assertSame('Sonali Bank Ltd', $bank->fresh()->name);

        // Duplicate name is rejected by the unique rule (validation failures use a 200 envelope, matching ValidateStoreAdminUserRole).
        LibBank::create(['name' => 'Janata Bank']);
        $this->postJson($base, ['name' => 'Janata Bank'])->assertOk()->assertJsonPath('success', false);

        $this->postJson($base.'/delete-list', ['ids' => [$bank->id]])->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseMissing('lib_banks', ['id' => $bank->id]);
    }

    public function test_division_district_thana_hierarchy_and_scoped_uniqueness(): void
    {
        $this->withToken($this->token($this->admin()));

        $division = $this->postJson('/api/v1/admin/datalibrary/location/division', ['name' => 'Dhaka'])
            ->assertOk();
        $divisionId = LibDivision::where('name', 'Dhaka')->firstOrFail()->id;

        $districtBase = '/api/v1/admin/datalibrary/location/district';
        $this->postJson($districtBase, ['name' => 'Gazipur', 'lib_division_id' => $divisionId])
            ->assertOk()->assertJsonPath('success', true);
        $district = LibDistrict::where('name', 'Gazipur')->firstOrFail();
        $this->assertSame($divisionId, $district->lib_division_id);

        // Same district name allowed under a different division (composite-unique, not global-unique).
        $otherDivisionId = LibDivision::create(['name' => 'Chattogram'])->id;
        $this->postJson($districtBase, ['name' => 'Gazipur', 'lib_division_id' => $otherDivisionId])
            ->assertOk()->assertJsonPath('success', true);

        // Same district name in the SAME division is rejected.
        $this->postJson($districtBase, ['name' => 'Gazipur', 'lib_division_id' => $divisionId])
            ->assertOk()->assertJsonPath('success', false);

        $thanaBase = '/api/v1/admin/datalibrary/location/thana';
        $this->postJson($thanaBase, [
            'name' => 'Sreepur', 'lib_division_id' => $divisionId, 'lib_district_id' => $district->id,
        ])->assertOk()->assertJsonPath('success', true);

        $this->postJson($districtBase.'/list')->assertOk()->assertJsonFragment(['name' => 'Gazipur']);
        $this->postJson($thanaBase.'/list')->assertOk()->assertJsonFragment(['name' => 'Sreepur']);
    }

    public function test_datalibrary_routes_require_admin_authentication(): void
    {
        $this->getJson('/api/v1/admin/datalibrary/bank')->assertUnauthorized()->assertJsonPath('success', false);
        $this->postJson('/api/v1/admin/datalibrary/location/thana', ['name' => 'X'])->assertUnauthorized();
    }

    public function test_datalibrary_web_pages_render_for_an_authenticated_admin(): void
    {
        $this->actingAs($this->admin(), 'admin');
        foreach ([
            'datalibrary/bank', 'datalibrary/board', 'datalibrary/degree', 'datalibrary/skill',
            'datalibrary/location/division', 'datalibrary/location/district', 'datalibrary/location/thana',
        ] as $path) {
            $response = $this->get('/admin/'.$path)->assertOk();
            $this->assertStringContainsString('<!DOCTYPE html>', $response->getContent());
        }
    }
}
