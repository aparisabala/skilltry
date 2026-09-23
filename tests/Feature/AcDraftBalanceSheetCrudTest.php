<?php

namespace Tests\Feature;

use App\Models\AcBalanceSheet;
use App\Models\AcCashbook;
use App\Models\AcDraftBalanceSheet;
use App\Models\AcLedger;
use App\Models\AcTranItem;
use App\Models\AdminUser;
use App\Models\AdminUserPermission;
use App\Models\AdminUserRole;
use App\Http\Middleware\SetBootConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AcDraftBalanceSheetCrudTest extends TestCase
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

    private function ledger(string $type, float $opening = 0, string $name = null): AcLedger
    {
        return AcLedger::create([
            'name' => $name ?? Str::random(8), 'ledger_type' => $type, 'opening_balance' => $opening,
            'serial' => (int) AcLedger::where('ledger_type', $type)->max('serial') + 1,
        ]);
    }

    public function test_a_cash_income_draft_is_final_saved_into_the_real_books(): void
    {
        $this->withToken($this->token($this->admin()));
        $cash = $this->ledger('cash', 0, 'Main Cash');
        $income = $this->ledger('income', 0, 'Sales Income');

        // cash/income: credit_to is the cash ledger, debit_to is the income ledger (see getLergers()).
        $draft = $this->postJson('/api/v1/admin/account/transaction', [
            'tran_date' => now()->toDateString(), 'tran_type' => 'cash', 'tran_method' => 'income',
            'debit_to' => $income->id, 'credit_to' => $cash->id,
        ])->assertOk()->assertJsonPath('success', true);
        $draftId = AcDraftBalanceSheet::firstOrFail()->id;

        $this->postJson('/api/v1/admin/account/transaction/crud/manage-item', [
            'ac_draft_transaction_id' => $draftId, 'folio_number' => 'F1', 'description' => 'First line', 'amount' => 300,
        ])->assertOk()->assertJsonPath('success', true);
        $this->postJson('/api/v1/admin/account/transaction/crud/manage-item', [
            'ac_draft_transaction_id' => $draftId, 'folio_number' => 'F2', 'description' => 'Second line', 'amount' => 200,
        ])->assertOk()->assertJsonPath('success', true);

        $this->assertCount(2, AcDraftBalanceSheet::find($draftId)->items);

        $this->postJson('/api/v1/admin/account/transaction/crud/manage-item/save', ['ac_draft_transaction_id' => $draftId])
            ->assertOk()->assertJsonPath('success', true);

        // The draft and its items are gone.
        $this->assertDatabaseMissing('ac_draft_balance_sheets', ['id' => $draftId]);
        $this->assertDatabaseCount('ac_draft_balance_sheet_items', 0);

        // The real books now hold the posting.
        $cb = AcCashbook::where('ac_ledger_id', $cash->id)->firstOrFail();
        $this->assertSame('income', $cb->tran_method);
        $this->assertEquals(500, (float) $cb->total_amount);
        $bs = AcBalanceSheet::where('id', $cb->linked_to)->firstOrFail();
        $this->assertSame($income->id, $bs->ac_ledger_id);
        $this->assertEquals(500, (float) $bs->total_amount);
        $this->assertCount(2, AcTranItem::where('ac_cashbook_id', $cb->id)->get());
        $this->assertEquals(500, \App\Support\Account\AccountPosting::balance($cash->id));
    }

    public function test_a_bank_deposit_draft_goes_through_the_contra_path(): void
    {
        $this->withToken($this->token($this->admin()));
        $cash = $this->ledger('cash', 1000, 'Main Cash');
        $bank = $this->ledger('bank', 0, 'Main Bank');

        // bank/deposit: debit_to is the cash ledger the money leaves, credit_to is the bank ledger.
        $draft = $this->postJson('/api/v1/admin/account/transaction', [
            'tran_date' => now()->toDateString(), 'tran_type' => 'bank', 'tran_method' => 'deposit',
            'debit_to' => $cash->id, 'credit_to' => $bank->id,
        ])->assertOk()->assertJsonPath('success', true);
        $draftId = AcDraftBalanceSheet::firstOrFail()->id;

        $this->postJson('/api/v1/admin/account/transaction/crud/manage-item', [
            'ac_draft_transaction_id' => $draftId, 'folio_number' => 'D1', 'description' => 'Deposit', 'amount' => 400,
        ])->assertOk()->assertJsonPath('success', true);

        $this->postJson('/api/v1/admin/account/transaction/crud/manage-item/save', ['ac_draft_transaction_id' => $draftId])
            ->assertOk()->assertJsonPath('success', true);

        $this->assertDatabaseMissing('ac_draft_balance_sheets', ['id' => $draftId]);
        $this->assertDatabaseCount('ac_balance_sheets', 0); // a contra entry never touches the balance sheet
        $rows = AcCashbook::where('entry_kind', 'contra')->get();
        $this->assertCount(2, $rows);
        $this->assertEquals($rows[0]->id, $rows[1]->linked_to);
        $this->assertEquals($rows[1]->id, $rows[0]->linked_to);
        $this->assertEquals(600, \App\Support\Account\AccountPosting::balance($cash->id));
        $this->assertEquals(400, \App\Support\Account\AccountPosting::balance($bank->id));
    }

    public function test_an_expense_draft_that_would_overdraw_the_cash_ledger_is_rejected(): void
    {
        $this->withToken($this->token($this->admin()));
        $cash = $this->ledger('cash', 0, 'Empty Cash');
        $expense = $this->ledger('expense', 0, 'Office Expense');

        // cash/expense: debit_to is the cash ledger, credit_to is the expense ledger (see getLergers()).
        $this->postJson('/api/v1/admin/account/transaction', [
            'tran_date' => now()->toDateString(), 'tran_type' => 'cash', 'tran_method' => 'expense',
            'debit_to' => $cash->id, 'credit_to' => $expense->id,
        ])->assertOk();
        $draftId = AcDraftBalanceSheet::firstOrFail()->id;
        $this->postJson('/api/v1/admin/account/transaction/crud/manage-item', [
            'ac_draft_transaction_id' => $draftId, 'folio_number' => 'X1', 'description' => 'Too much', 'amount' => 999,
        ])->assertOk();

        $this->postJson('/api/v1/admin/account/transaction/crud/manage-item/save', ['ac_draft_transaction_id' => $draftId])
            ->assertOk()->assertJsonPath('success', false);

        // Nothing was posted, and the draft survives so it can be corrected.
        $this->assertDatabaseHas('ac_draft_balance_sheets', ['id' => $draftId]);
        $this->assertDatabaseCount('ac_cashbooks', 0);
    }

    public function test_draft_transaction_routes_require_admin_authentication(): void
    {
        $this->getJson('/api/v1/admin/account/transaction/cash/income')->assertUnauthorized()->assertJsonPath('success', false);
    }

    public function test_draft_transaction_permission_is_enforced_and_can_be_granted(): void
    {
        AdminUserPermission::forceCreate(['slug' => 'mobile_api_access', 'user_access' => ['ST']]);
        $staff = $this->admin('ST');
        $this->withToken($this->token($staff));

        $this->getJson('/api/v1/admin/account/transaction/cash/income')->assertForbidden();

        AdminUserPermission::forceCreate(['slug' => 'ac_draft_balance_sheet_crud_view', 'user_access' => ['ST']]);
        $this->getJson('/api/v1/admin/account/transaction/cash/income')->assertOk()->assertJsonPath('success', true);
    }
}
