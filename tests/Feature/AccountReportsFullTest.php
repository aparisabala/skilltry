<?php

namespace Tests\Feature;

use App\Models\AcLedger;
use App\Support\Account\AccountPosting;
use App\Support\Account\AccountReports;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountReportsFullTest extends TestCase
{
    use RefreshDatabase;

    private function ledger(string $type, float $opening = 0, string $name = null): AcLedger
    {
        return AcLedger::create([
            'name' => $name ?? uniqid($type.'-'), 'ledger_type' => $type, 'opening_balance' => $opening,
            'serial' => (int) AcLedger::where('ledger_type', $type)->max('serial') + 1,
        ]);
    }

    private function book(): array
    {
        $cash = $this->ledger('cash', 1000, 'Main Cash');
        $bank = $this->ledger('bank', 0, 'Main Bank');
        $income = $this->ledger('income', 0, 'Sales Income');
        $expense = $this->ledger('expense', 0, 'Office Expense');

        // Income into cash, expense out of cash, a deposit into the bank.
        AccountPosting::post(['tran_type' => 'cash', 'tran_method' => 'income', 'cash_ledger_id' => $cash->id, 'counter_ledger_id' => $income->id, 'items' => [['description' => 'Sale', 'amount' => 700]]]);
        AccountPosting::post(['tran_type' => 'cash', 'tran_method' => 'expense', 'cash_ledger_id' => $cash->id, 'counter_ledger_id' => $expense->id, 'items' => [['description' => 'Rent', 'amount' => 300]]]);
        AccountPosting::contra(['from_ledger_id' => $cash->id, 'to_ledger_id' => $bank->id, 'items' => [['description' => 'Deposit', 'amount' => 400]]]);

        return compact('cash', 'bank', 'income', 'expense');
    }

    public function test_trial_balance_debits_equal_credits(): void
    {
        $this->book();
        $report = AccountReports::run('trial_balance');
        $this->assertEquals($report['totals']['debit'], $report['totals']['credit']);
    }

    public function test_ledger_statement_running_balance_is_correct(): void
    {
        $l = $this->book();
        $report = AccountReports::run('ledger_statement', ['ledger_id' => $l['cash']->id]);
        $rows = $report['rows'];
        // opening (1000) + income (700) - expense (300) - deposit-out (400) = 1000
        $this->assertEquals(1000, (float) end($rows)['balance']);
        $this->assertEquals(1000, AccountPosting::balance($l['cash']->id));
    }

    public function test_day_book_lists_every_cash_and_bank_entry(): void
    {
        $this->book();
        $report = AccountReports::run('day_book');
        // income + expense + 2 contra legs of the deposit = 4 cashbook rows
        $this->assertCount(4, $report['rows']);
        $this->assertEquals(1100, $report['totals']['received']); // 700 income + 400 deposit-in
        $this->assertEquals(700, $report['totals']['paid']); // 300 expense + 400 deposit-out
    }

    public function test_income_statement_shows_surplus(): void
    {
        $this->book();
        $report = AccountReports::run('income_statement');
        $last = end($report['rows']);
        $this->assertSame('Surplus', $last['section']);
        $this->assertEquals(400, $last['signed']); // 700 income - 300 expense
    }

    public function test_income_statement_shows_deficit_when_expense_exceeds_income(): void
    {
        $cash = $this->ledger('cash', 1000, 'Deficit Cash');
        $income = $this->ledger('income', 0, 'Small Income');
        $expense = $this->ledger('expense', 0, 'Big Expense');
        AccountPosting::post(['tran_type' => 'cash', 'tran_method' => 'income', 'cash_ledger_id' => $cash->id, 'counter_ledger_id' => $income->id, 'items' => [['description' => 'Sale', 'amount' => 100]]]);
        AccountPosting::post(['tran_type' => 'cash', 'tran_method' => 'expense', 'cash_ledger_id' => $cash->id, 'counter_ledger_id' => $expense->id, 'items' => [['description' => 'Rent', 'amount' => 900]]]);

        $report = AccountReports::run('income_statement');
        $last = end($report['rows']);
        $this->assertSame('Deficit', $last['section']);
        $this->assertEquals(-800, $last['signed']);
    }

    public function test_cash_bank_position_reports_opening_and_closing(): void
    {
        $l = $this->book();
        $report = AccountReports::run('cash_bank_position');
        $rows = collect($report['rows']);
        $cashRow = $rows->firstWhere('name', $l['cash']->name);
        $this->assertEquals(1000, $cashRow['opening']);
        $this->assertEquals(1000, $cashRow['closing']);
    }

    public function test_catalogue_excludes_referral_and_patient_reports(): void
    {
        $keys = array_keys(AccountReports::CATALOGUE);
        foreach (['referral_dues', 'referral_commissions', 'referral_payments', 'referral_by_source', 'patient_collections', 'wallet_stream'] as $excluded) {
            $this->assertNotContains($excluded, $keys);
        }
    }
}
