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

class AccountMobileApiTest extends TestCase
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

    private function admin(string $code = 'SA'): AdminUser
    {
        $role = AdminUserRole::firstOrCreate(['code' => $code], ['name' => $code]);
        $user = new AdminUser;
        $user->forceFill([
            'uuid' => (string) Str::uuid(), 'name' => 'Mobile Account Admin',
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

    private function ledger(string $type, float $opening = 0, string $name = null): AcLedger
    {
        return AcLedger::create([
            'name' => $name ?? uniqid($type.'-'), 'ledger_type' => $type, 'opening_balance' => $opening,
            'serial' => (int) AcLedger::where('ledger_type', $type)->max('serial') + 1,
        ]);
    }

    private function grantAll(string $code): void
    {
        foreach (['ac_ledger_crud_view', 'ac_ledger_crud_store', 'ac_ledger_crud_edit', 'ac_report_view', 'ac_draft_balance_sheet_item_crud_edit'] as $slug) {
            AdminUserPermission::forceCreate(['slug' => $slug, 'user_access' => [$code]]);
        }
    }

    public function test_ledger_list_includes_a_computed_live_balance(): void
    {
        $admin = $this->admin();
        $this->grantAll('SA');
        $this->withToken($this->token($admin));
        $cash = $this->ledger('cash', 500, 'Mobile Cash');

        $res = $this->getJson('/api/v1/admin/account/ledgers?type=cash')->assertOk()->assertJsonPath('success', true);
        $row = collect($res->json('data'))->firstWhere('id', $cash->id);
        $this->assertEquals(500, $row['balance']);
    }

    public function test_ledger_statement_endpoint_runs_the_report(): void
    {
        $admin = $this->admin();
        $this->grantAll('SA');
        $this->withToken($this->token($admin));
        $cash = $this->ledger('cash', 250, 'Statement Cash');

        $this->getJson('/api/v1/admin/account/ledgers/'.$cash->id.'/statement')
            ->assertOk()->assertJsonPath('success', true)->assertJsonPath('data.key', 'ledger_statement');
    }

    public function test_post_receive_pay_deposit_and_withdraw_via_kind(): void
    {
        $admin = $this->admin();
        $this->grantAll('SA');
        $this->withToken($this->token($admin));
        $cash = $this->ledger('cash', 0, 'Kind Cash');
        $bank = $this->ledger('bank', 0, 'Kind Bank');
        $income = $this->ledger('income', 0, 'Kind Income');
        $expense = $this->ledger('expense', 0, 'Kind Expense');

        $this->postJson('/api/v1/admin/account/transactions', [
            'kind' => 'receive', 'method' => 'cash', 'ledger_id' => $cash->id, 'counter_ledger_id' => $income->id,
            'items' => [['description' => 'Sale', 'amount' => 500]],
        ])->assertStatus(201)->assertJsonPath('success', true);

        $this->postJson('/api/v1/admin/account/transactions', [
            'kind' => 'pay', 'method' => 'cash', 'ledger_id' => $cash->id, 'counter_ledger_id' => $expense->id,
            'items' => [['description' => 'Rent', 'amount' => 100]],
        ])->assertStatus(201)->assertJsonPath('success', true);

        $this->postJson('/api/v1/admin/account/transactions', [
            'kind' => 'deposit', 'ledger_id' => $cash->id, 'counter_ledger_id' => $bank->id,
            'items' => [['description' => 'Deposit', 'amount' => 200]],
        ])->assertStatus(201)->assertJsonPath('success', true);

        $this->postJson('/api/v1/admin/account/transactions', [
            'kind' => 'withdraw', 'ledger_id' => $bank->id, 'counter_ledger_id' => $cash->id,
            'items' => [['description' => 'Withdraw', 'amount' => 50]],
        ])->assertStatus(201)->assertJsonPath('success', true);

        $this->assertEquals(250, \App\Support\Account\AccountPosting::balance($cash->id)); // 500 - 100 - 200 + 50
        $this->assertEquals(150, \App\Support\Account\AccountPosting::balance($bank->id)); // 200 - 50
    }

    public function test_post_transaction_rejects_insufficient_balance(): void
    {
        $admin = $this->admin();
        $this->grantAll('SA');
        $this->withToken($this->token($admin));
        $cash = $this->ledger('cash', 0, 'Empty Kind Cash');
        $expense = $this->ledger('expense', 0, 'Kind Expense 2');

        $this->postJson('/api/v1/admin/account/transactions', [
            'kind' => 'pay', 'method' => 'cash', 'ledger_id' => $cash->id, 'counter_ledger_id' => $expense->id,
            'items' => [['description' => 'Too much', 'amount' => 999]],
        ])->assertStatus(422)->assertJsonPath('success', false);
    }

    public function test_mobile_api_routes_require_admin_authentication(): void
    {
        $this->getJson('/api/v1/admin/account/ledgers')->assertUnauthorized()->assertJsonPath('success', false);
    }
}
