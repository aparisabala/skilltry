<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\AdminUserRole;
use App\Models\AdminUserPermission;
use App\Http\Middleware\Api\AuthenticateAdminApi;
use App\Http\Middleware\SetBootConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class HrmApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Boot branding/uploads is unrelated to the HR/auth integration.
        $this->withoutMiddleware(SetBootConfig::class);
        if (!defined('V')) {
            define('V', 'test');
        }
    }

    private function admin(string $code = 'SA', array $attributes = []): AdminUser
    {
        $role = AdminUserRole::firstOrCreate(['code' => $code], ['name' => $code]);
        $user = new AdminUser;
        $user->forceFill(array_merge([
            'uuid' => (string) Str::uuid(), 'name' => 'HR Administrator',
            'email' => Str::uuid().'@example.test', 'password' => Hash::make('test-password-123'),
            'admin_user_role_id' => $role->id, 'setup_done' => 'yes',
            'admin_type' => 'system_user', 'status' => 'Active',
        ], $attributes))->save();
        return $user;
    }

    private function token(AdminUser $user): string
    {
        return $user->createToken('tests', ['*'], now()->addHour())->plainTextToken;
    }

    public function test_every_admin_web_route_has_a_protected_api_mirror(): void
    {
        $routes = collect(Route::getRoutes());
        $web = $routes->filter(fn ($route) => str_starts_with($route->uri(), 'admin/'));
        $this->assertGreaterThan(20, $web->count());
        foreach ($web as $route) {
            $api = $routes->first(fn ($candidate) => $candidate->uri() === 'api/v1/'.$route->uri()
                && $candidate->methods() === $route->methods());
            $this->assertNotNull($api, $route->uri());
            $this->assertContains(AuthenticateAdminApi::class, $api->gatherMiddleware());
            if ($route->getName()) {
                $this->assertSame('api.v1.'.$route->getName(), $api->getName());
            }
            $action = $api->getActionName();
            [$controller, $method] = explode('@', $action);
            $this->assertStringStartsWith('App\\Http\\Controllers\\Api\\V1\\Admin\\', $controller);
            $this->assertFalse(is_subclass_of($controller, $route->getControllerClass()));
            $this->assertTrue(method_exists($controller, $method), $action);
        }
    }

    public function test_login_issues_admin_token_and_logout_revokes_it(): void
    {
        $admin = $this->admin();
        $login = $this->postJson('/api/v1/admin/auth/login', [
            'email' => $admin->email, 'password' => 'test-password-123', 'device_name' => 'tests',
        ])->assertOk()->assertJsonPath('success', true);
        $token = $login->json('data.token');
        $this->assertNotEmpty($token);
        $this->withToken($token)->getJson('/api/v1/admin/auth/me')
            ->assertOk()->assertJsonPath('data.user.id', $admin->id);
        $this->withToken($token)->postJson('/api/v1/admin/auth/logout')->assertOk();
        $this->withToken($token)->getJson('/api/v1/admin/auth/me')->assertUnauthorized();
    }

    public function test_login_validation_bad_credentials_and_disabled_accounts(): void
    {
        $this->post('/api/v1/admin/auth/login')->assertStatus(422)->assertJsonValidationErrors(['email', 'password']);
        $admin = $this->admin();
        $this->postJson('/api/v1/admin/auth/login', ['email' => $admin->email, 'password' => 'incorrect'])->assertUnauthorized();
        $admin->forceFill(['status' => 'Disabled'])->save();
        $this->postJson('/api/v1/admin/auth/login', ['email' => $admin->email, 'password' => 'test-password-123'])->assertForbidden();
        $this->withToken($this->token($admin))->getJson('/api/v1/admin/dashboard')->assertForbidden();
    }

    public function test_admin_api_rejects_missing_expired_and_non_admin_tokens_and_session_only_auth(): void
    {
        $admin = $this->admin();
        $this->get('/api/v1/admin/hrm/user/user-role')->assertUnauthorized()->assertJsonPath('success', false);
        $this->actingAs($admin, 'admin')->get('/api/v1/admin/hrm/user/user-role')->assertUnauthorized();
        $expired = $admin->createToken('expired', ['*'], now()->subMinute())->plainTextToken;
        $this->withToken($expired)->getJson('/api/v1/admin/dashboard')->assertUnauthorized();
        $other = \App\Models\User::factory()->create();
        // A token for another provider must not authenticate as an administrator.
        $plain = Str::random(40);
        DB::table('personal_access_tokens')->insert([
            'tokenable_type' => get_class($other), 'tokenable_id' => $other->id,
            'name' => 'wrong-provider', 'token' => hash('sha256', $plain), 'abilities' => '["*"]',
        ]);
        $this->withToken($plain)->getJson('/api/v1/admin/dashboard')->assertUnauthorized();
    }

    public function test_unfinished_profile_can_access_setup_but_not_hr(): void
    {
        $admin = $this->admin('SA', ['setup_done' => 'no']);
        $this->withToken($this->token($admin));
        $this->getJson('/api/v1/admin/hrm/user/user-role')->assertForbidden()->assertJsonPath('code', 'setup_required');
        $this->getJson('/api/v1/admin/setup/profile')->assertOk()->assertJsonPath('data.item.id', $admin->id);
        $this->getJson('/api/v1/admin/auth/me')->assertOk()->assertJsonPath('data.setup_required', true);
    }

    public function test_api_uses_token_owner_even_if_auth_uuid_is_spoofed(): void
    {
        $admin = $this->admin();
        $other = $this->admin('ST');
        $this->withToken($this->token($admin))->postJson('/api/v1/admin/setup/profile-update', [
            'auth_uuid' => $other->uuid, 'auth' => ['id' => $other->id],
            'name' => 'Updated Actor', 'email' => $admin->email, 'mobile_number' => '01712345678',
        ])->assertOk()->assertJsonPath('success', true);
        $this->assertSame('Updated Actor', $admin->fresh()->name);
        $this->assertSame('HR Administrator', $other->fresh()->name);
    }

    public function test_hr_permissions_are_enforced_and_updates_take_effect(): void
    {
        AdminUserPermission::forceCreate(['slug' => 'mobile_api_access', 'user_access' => ['ST']]);
        $staff = $this->admin('ST');
        $this->withToken($this->token($staff));
        $this->getJson('/api/v1/admin/hrm/user/user-role')->assertForbidden();
        AdminUserPermission::forceCreate(['slug' => 'hrm_user_roles_view', 'user_access' => ['ST']]);
        $this->getJson('/api/v1/admin/hrm/user/user-role')->assertOk();
        $this->postJson('/api/v1/admin/hrm/user/user-role', ['name' => 'New role', 'code' => 'NEW'])->assertForbidden();
        AdminUserPermission::where('slug', 'hrm_user_roles_view')->update(['user_access' => []]);
        $this->getJson('/api/v1/admin/hrm/user/user-role')->assertForbidden();
    }

    public function test_mobile_api_permission_controls_login_and_existing_tokens(): void
    {
        $staff = $this->admin('ST');
        $credentials = ['email' => $staff->email, 'password' => 'test-password-123'];
        $this->postJson('/api/v1/admin/auth/login', $credentials)->assertForbidden();
        $this->assertSame(0, $staff->tokens()->count());
        AdminUserPermission::forceCreate(['slug' => 'mobile_api_access', 'user_access' => ['ST']]);
        $token = $this->postJson('/api/v1/admin/auth/login', $credentials)->assertOk()->json('data.token');
        $this->withToken($token)->getJson('/api/v1/admin/auth/me')->assertOk();
        AdminUserPermission::where('slug', 'mobile_api_access')->update(['user_access' => []]);
        $this->getJson('/api/v1/admin/auth/me')->assertForbidden();
    }

    public function test_hr_sidebar_only_displays_permitted_sections(): void
    {
        $staff = $this->admin('ST');
        $this->actingAs($staff, 'admin');
        $data = ['userRoles' => AdminUserRole::all()];
        $this->assertStringNotContainsString('<li', view('admin.includes._fragments._human-resources', compact('data'))->render());
        AdminUserPermission::forceCreate(['slug' => 'hrm_user_roles_view', 'user_access' => ['ST']]);
        $html = view('admin.includes._fragments._human-resources', compact('data'))->render();
        $this->assertStringContainsString('admin/hrm/user/user-role', $html);
        $this->assertStringNotContainsString('admin/hrm/user/user-policy', $html);
        $this->assertStringNotContainsString('admin/hrm/user/user-list', $html);
    }

    public function test_roles_use_the_existing_repository_for_create_update_list_and_delete(): void
    {
        $this->withToken($this->token($this->admin()));
        $base = '/api/v1/admin/hrm/user/user-role';
        $this->postJson($base, ['name' => 'Teacher', 'code' => 'TC'])->assertOk()->assertJsonPath('success', true);
        $role = AdminUserRole::where('code', 'TC')->firstOrFail();
        $this->putJson($base.'/'.$role->id, ['name' => 'Senior Teacher', 'code' => 'TC'])
            ->assertOk()->assertJsonPath('success', true);
        $this->postJson($base.'/list')->assertOk()->assertJsonFragment(['name' => 'Senior Teacher']);
        $this->postJson($base.'/delete-list', ['ids' => [$role->id]])->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseMissing('admin_user_roles', ['id' => $role->id]);
    }

    public function test_new_hr_pages_return_json_and_do_not_expose_password_reset_secrets(): void
    {
        $admin = $this->admin();
        $staff = $this->admin('ST', ['reset_code' => '1234567']);
        $this->withToken($this->token($admin));
        foreach ([
            'hrm/user/user-role', 'hrm/user/user-role/create',
            'hrm/user/user-list/'.$staff->admin_user_role_id, 'hrm/user/'.$staff->id.'/edit',
            'hrm/user/create?admin_user_role_id='.$staff->admin_user_role_id,
            'hrm/user/user-policy', 'dashboard', 'setup/profile-update', 'setup/password-update', 'login', 'reset',
        ] as $path) {
            $response = $this->getJson('/api/v1/admin/'.$path)->assertOk()->assertJsonPath('success', true);
            $this->assertStringNotContainsString('1234567', $response->getContent(), $path);
            $this->assertStringNotContainsString('"password":', $response->getContent(), $path);
        }
        $this->postJson('/api/v1/admin/hrm/user/policy/update-policy-item/display')
            ->assertOk()->assertJsonStructure(['data' => ['permissions', 'systePolicies']]);
    }

    public function test_web_hr_still_renders_blade_with_session_guard(): void
    {
        $staff = $this->admin('ST');
        $this->actingAs($this->admin(), 'admin');
        foreach ([
            'user-role', 'user-list/'.$staff->admin_user_role_id, $staff->id.'/edit', 'user-policy',
        ] as $path) {
            $response = $this->get('/admin/hrm/user/'.$path)->assertOk();
            $this->assertStringContainsString('<!DOCTYPE html>', $response->getContent());
        }
        $this->postJson('/admin/hrm/user/policy/update-policy-item/display', ['policy_name' => 'Hrm Management Policies'])
            ->assertOk()->assertJsonPath('success', true)->assertJsonStructure(['data' => ['view']]);
    }

    public function test_user_upload_create_list_update_and_delete_keep_hms_behavior(): void
    {
        $admin = $this->admin();
        $role = AdminUserRole::create(['name' => 'Teacher', 'code' => 'TC']);
        // Exercise real image processing in an isolated temporary directory.
        $working = getcwd();
        $temporary = storage_path('framework/testing/hrm-'.Str::uuid());
        mkdir($temporary.'/uploads/app/test/dyn/images', 0777, true);
        config(['i.service_domain' => 'test']);
        chdir($temporary);
        try {
            $this->withToken($this->token($admin))->post('/api/v1/admin/hrm/user', [
                'admin_type' => 'system_user', 'name' => 'Teacher One', 'email' => 'teacher@example.test',
                'mobile_number' => '01799999999', 'admin_user_role_id' => $role->id,
                'image' => \Illuminate\Http\UploadedFile::fake()->image('avatar.jpg'),
            ])->assertOk()->assertJsonPath('success', true);
            $user = AdminUser::where('email', 'teacher@example.test')->firstOrFail();
            $this->assertTrue(Hash::check('123456789', $user->password));
            $this->assertFileExists(imagePaths()['dyn_image'].$user->image.'_80X80.jpg');
            $this->postJson('/api/v1/admin/hrm/user/list', ['admin_user_role_id' => $role->id])
                ->assertOk()->assertJsonFragment(['email' => 'teacher@example.test']);
            $this->patchJson('/api/v1/admin/hrm/user/'.$user->id, [
                'name' => $user->name, 'email' => $user->email, 'mobile_number' => $user->mobile_number, 'status' => 'Disabled',
            ])->assertOk()->assertJsonPath('success', true);
            $this->assertSame('Disabled', $user->fresh()->status);
            $this->postJson('/api/v1/admin/hrm/user/delete-list', ['ids' => [$user->id]])
                ->assertOk()->assertJsonPath('success', true);
            $this->assertDatabaseMissing('admin_users', ['id' => $user->id]);
        } finally {
            chdir($working);
            \Illuminate\Support\Facades\File::deleteDirectory($temporary);
        }
    }

    public function test_permission_update_endpoint_persists_hms_role_access(): void
    {
        $this->withToken($this->token($this->admin()));
        $this->getJson('/api/v1/admin/hrm/user/user-policy')->assertOk();
        $permission = AdminUserPermission::where('slug', 'hrm_user_view')->firstOrFail();
        $this->postJson('/api/v1/admin/hrm/user/user-policy', [
            'slug' => [$permission->id], 'user_access' => [$permission->id => ['TC']],
        ])->assertOk()->assertJsonPath('success', true);
        $this->assertSame(['TC'], $permission->fresh()->user_access);
    }

    public function test_reset_mirror_cannot_change_another_administrators_password(): void
    {
        $actor = $this->admin();
        $other = $this->admin();
        $this->withToken($this->token($actor))->postJson('/api/v1/admin/reset/change-pass', [
            'user_uuid' => $other->uuid, 'password' => 'new-password-123', 'confirm_password' => 'new-password-123',
        ])->assertForbidden();
        $this->assertTrue(Hash::check('test-password-123', $other->fresh()->password));
    }

    public function test_api_setup_and_password_repositories_update_the_token_owner(): void
    {
        $user = $this->admin('SA', ['setup_done' => 'no']);
        $this->withToken($this->token($user));
        $this->postJson('/api/v1/admin/setup/profile', [
            'name' => 'Setup Admin', 'email' => $user->email, 'mobile_number' => '01712345678',
            'new_password' => 'setup-password-123', 'confim_password' => 'setup-password-123',
        ])->assertOk()->assertJsonPath('success', true);
        $this->assertSame('yes', $user->fresh()->setup_done);
        $this->postJson('/api/v1/admin/setup/password-update', [
            'old_password' => 'setup-password-123', 'password' => 'changed-password-123',
            'confirm_password' => 'changed-password-123',
        ])->assertOk()->assertJsonPath('success', true);
        $this->assertTrue(Hash::check('changed-password-123', $user->fresh()->password));
    }

    public function test_api_reset_repository_returns_json_steps(): void
    {
        \Illuminate\Support\Facades\Mail::fake();
        $user = $this->admin();
        $this->withToken($this->token($user));
        $this->postJson('/api/v1/admin/reset/send-code')->assertOk()->assertJsonPath('data.next_step', 'verify-code');
        $this->postJson('/api/v1/admin/reset/verify-code', ['code' => $user->fresh()->reset_code])
            ->assertOk()->assertJsonPath('data.next_step', 'change-pass');
        $this->postJson('/api/v1/admin/reset/change-pass', [
            'password' => 'reset-password-123', 'confirm_password' => 'reset-password-123',
        ])->assertOk()->assertJsonPath('success', true);
        $this->assertTrue(Hash::check('reset-password-123', $user->fresh()->password));
    }

    public function test_scramble_docs_cover_api_authentication_and_request_payloads(): void
    {
        $this->app->instance('env', 'local');
        $this->get('/docs/api')->assertOk()->assertSee('Biddaloy Admin API');
        $spec = $this->getJson('/docs/api.json')->assertOk()->json();
        $this->assertSame('bearer', $spec['components']['securitySchemes']['http']['scheme']);
        $this->assertSame([], $spec['paths']['/admin/auth/login']['post']['security']);
        $this->assertNotEmpty($spec['security']);
        $this->assertSame('Human Resource / User Role', $spec['tags'][4]['name']);
        $this->assertSame(['Human Resource / User'], $spec['paths']['/admin/hrm/user']['post']['tags']);
        $this->assertStringContainsString('hrm_user_store', $spec['paths']['/admin/hrm/user']['post']['description']);
        $this->assertArrayHasKey('multipart/form-data', $spec['paths']['/admin/hrm/user']['post']['requestBody']['content']);
        $this->assertArrayHasKey('multipart/form-data', $spec['paths']['/admin/setup/profile']['post']['requestBody']['content']);
        $policy = $spec['paths']['/admin/hrm/user/user-policy']['post']['requestBody']['content']['application/json']['schema'];
        $this->assertArrayHasKey('slug', $policy['properties']);
        $this->assertArrayHasKey('user_access', $policy['properties']);
        $paths = array_map(fn ($path) => preg_replace('/\{[^}]+\}/', '{}', $path), array_keys($spec['paths']));
        foreach (Route::getRoutes() as $route) {
            if (str_starts_with($route->uri(), 'api/v1/')) {
                $this->assertContains(preg_replace('/\{[^}]+\}/', '{}', substr($route->uri(), 6)), $paths);
            }
        }
    }

    public function test_documentation_visibility_follows_hms_pattern(): void
    {
        config(['hrm.api_docs' => 'public']);
        $this->get('/docs/api')->assertOk();
        $this->app->instance('env', 'local');
        config(['hrm.api_docs' => 'off']);
        $this->get('/docs/api')->assertForbidden();
        $this->getJson('/docs/api.json')->assertForbidden();
    }

    public function test_documentation_is_not_public_outside_local_environment(): void
    {
        $this->get('/docs/api')->assertForbidden();
        $this->getJson('/docs/api.json')->assertForbidden();
    }
}
