<?php

namespace Tests\Feature;

use App\Models\AcLedger;
use App\Models\AdminUser;
use App\Models\AdminUserPermission;
use App\Models\AdminUserRole;
use App\Http\Middleware\SetBootConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AcLedgerCrudTest extends TestCase
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

    private function admin(string $code = 'SA', array $attributes = []): AdminUser
    {
        $role = AdminUserRole::firstOrCreate(['code' => $code], ['name' => $code]);
        $user = new AdminUser;
        $user->forceFill(array_merge([
            'uuid' => (string) Str::uuid(), 'name' => 'Account Administrator',
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

    public function test_create_list_update_and_delete_ledgers_scoped_by_type(): void
    {
        $this->withToken($this->token($this->admin()));

        $this->postJson('/api/v1/admin/account/ledger', ['name' => 'Main Cash', 'ledger_type' => 'cash', 'opening_balance' => 500])
            ->assertOk()->assertJsonPath('success', true);
        $this->postJson('/api/v1/admin/account/ledger', ['name' => 'Sales Income', 'ledger_type' => 'income'])
            ->assertOk()->assertJsonPath('success', true);

        $cash = AcLedger::where('name', 'Main Cash')->firstOrFail();
        $this->assertEquals(500.0, (float) $cash->opening_balance);
        $this->assertSame(1, (int) $cash->serial); // serial is scoped per ledger_type, so this is the first cash ledger

        // The datatable list for "cash" must not include the income ledger.
        $cashList = $this->postJson('/api/v1/admin/account/ledger/list', ['ledger_type' => 'cash'])->assertOk();
        $this->assertCount(1, $cashList->json('data'));
        $this->assertSame('Main Cash', $cashList->json('data.0.name'));

        $this->patchJson('/api/v1/admin/account/ledger/'.$cash->id, ['note' => 'Updated note'])
            ->assertOk()->assertJsonPath('success', true);
        $this->assertSame('Updated note', $cash->fresh()->note);

        $this->postJson('/api/v1/admin/account/ledger/delete-list', ['ids' => [$cash->id]])
            ->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseMissing('ac_ledgers', ['id' => $cash->id]);
    }

    public function test_duplicate_ledger_name_is_rejected(): void
    {
        $this->withToken($this->token($this->admin()));
        AcLedger::create(['name' => 'Main Bank', 'ledger_type' => 'bank', 'opening_balance' => 0, 'serial' => 1]);

        $this->postJson('/api/v1/admin/account/ledger', ['name' => 'Main Bank', 'ledger_type' => 'bank'])
            ->assertOk()->assertJsonPath('success', false);
    }

    public function test_unknown_ledger_type_is_rejected_on_index(): void
    {
        $this->withToken($this->token($this->admin()));
        $this->getJson('/api/v1/admin/account/ledger/not-a-real-type')->assertStatus(422);
    }

    public function test_ledger_routes_require_admin_authentication(): void
    {
        $this->getJson('/api/v1/admin/account/ledger/cash')->assertUnauthorized()->assertJsonPath('success', false);
    }

    public function test_ledger_permission_is_enforced_and_can_be_granted(): void
    {
        AdminUserPermission::forceCreate(['slug' => 'mobile_api_access', 'user_access' => ['ST']]);
        $staff = $this->admin('ST');
        $this->withToken($this->token($staff));

        $this->getJson('/api/v1/admin/account/ledger/cash')->assertForbidden();

        AdminUserPermission::forceCreate(['slug' => 'ac_ledger_crud_view', 'user_access' => ['ST']]);
        $this->getJson('/api/v1/admin/account/ledger/cash')->assertOk()->assertJsonPath('success', true);
    }

    public function test_web_page_renders_for_an_authenticated_admin(): void
    {
        $this->actingAs($this->admin(), 'admin');
        $response = $this->get('/admin/account/ledger/cash')->assertOk();
        $this->assertStringContainsString('<!DOCTYPE html>', $response->getContent());
    }
}
