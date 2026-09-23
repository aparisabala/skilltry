<?php

namespace App\Support\Account;

use App\Models\AcLedger;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Account reports, one engine for the web pages and the API.
 *
 * run($key, $filters) answers: title, filters used, columns (field => label, type, total), rows and totals.
 * Column types: text, int, money, date, datetime, badge, qty.
 * Filters: date_from, date_to, ledger_id, ledger_type.
 *
 * Every figure comes from the books the way AccountPosting writes them: a cashbook row is a debit when it is income and a credit when
 * it is expense, a balance sheet row the other way round. The natural side of a ledger is debit for cash, bank, asset and expense
 * and credit for income.
 */
class AccountReports
{
    public const MAX_ROWS = 5000;

    /** key => [group, title, description, filters that apply, permission] */
    public const CATALOGUE = [
        'trial_balance' => ['Books', 'Trial balance', 'Debit and credit of every ledger up to the end date, opening balances included.', ['to'], 'ac_report_view'],
        'ledger_balances' => ['Books', 'Ledger balances', 'Opening balance, money in and out of the period and the closing balance of every ledger.', ['date', 'ledger_type'], 'ac_report_view'],
        'ledger_statement' => ['Books', 'Ledger statement', 'Every line of one ledger with a running balance.', ['date', 'ledger'], 'ac_report_view'],
        'day_book' => ['Books', 'Day book', 'Every cash and bank entry of the period with the ledger on the other side.', ['date', 'ledger_type'], 'ac_report_view'],
        'cash_bank_position' => ['Cash & bank', 'Cash and bank position', 'Opening, received, paid and closing balance of every cash and bank ledger.', ['date'], 'ac_report_view'],
        'daily_cash_flow' => ['Cash & bank', 'Daily cash flow', 'Money received and paid each day (moves between cash and bank left out).', ['date'], 'ac_report_view'],
        'income_statement' => ['Profit', 'Income and expense', 'Income and expense ledgers of the period with the surplus or deficit.', ['date'], 'ac_report_view'],
    ];

    public static function catalogue(?callable $can = null): array
    {
        $out = [];
        foreach (self::CATALOGUE as $key => [$group, $title, $desc, $filters, $perm]) {
            if ($can === null || $can($perm)) {
                $out[] = ['key' => $key, 'group' => $group, 'title' => $title, 'description' => $desc, 'filters' => $filters, 'permission' => $perm];
            }
        }
        return $out;
    }

    public static function filters(array $in): array
    {
        return [
            'date_from' => !empty($in['date_from']) ? Carbon::parse($in['date_from'])->toDateString() : now()->startOfMonth()->toDateString(),
            'date_to' => !empty($in['date_to']) ? Carbon::parse($in['date_to'])->toDateString() : now()->toDateString(),
            'ledger_id' => !empty($in['ledger_id']) ? (int) $in['ledger_id'] : null,
            'ledger_type' => !empty($in['ledger_type']) && in_array($in['ledger_type'], ['asset', 'cash', 'bank', 'income', 'expense'], true) ? (string) $in['ledger_type'] : null,
        ];
    }

    public static function run(string $key, array $input = []): ?array
    {
        if (!isset(self::CATALOGUE[$key])) {
            return null;
        }
        $f = self::filters($input);
        [$rows, $columns] = self::{'r_'.$key}($f);
        $rows = $rows instanceof Collection ? $rows->all() : $rows;
        $rows = array_map(fn($r) => (array) $r, array_slice($rows, 0, self::MAX_ROWS));
        $totals = [];
        foreach ($columns as $field => $c) {
            if (!empty($c[2])) {
                $totals[$field] = round(array_sum(array_column($rows, $field)), 2);
            }
        }
        return [
            'key' => $key, 'title' => self::CATALOGUE[$key][1], 'description' => self::CATALOGUE[$key][2], 'group' => self::CATALOGUE[$key][0], 'permission' => self::CATALOGUE[$key][4],
            'filters' => $f,
            'columns' => collect($columns)->map(fn($c) => ['label' => $c[0], 'type' => $c[1], 'total' => !empty($c[2])])->all(),
            'rows' => $rows, 'totals' => $totals, 'count' => count($rows),
        ];
    }

    // ---------------------------------------------------------------------------------------------- the books

    /**
     * Every line of the books as debit / credit: cashbook rows (income = debit, expense = credit) and balance sheet rows (income = credit, expense = debit)
     */
    private static function lines(): string
    {
        return "(SELECT c.id AS ref_id, 'cashbook' AS src, c.tran_date, c.ac_ledger_id AS ledger_id, c.tran_type, c.tran_method, c.entry_kind, c.linked_to,
                    CASE WHEN c.tran_method = 'income' THEN c.total_amount ELSE 0 END AS debit, CASE WHEN c.tran_method = 'expense' THEN c.total_amount ELSE 0 END AS credit FROM ac_cashbooks c
                UNION ALL
                SELECT b.id, 'balance_sheet', b.tran_date, b.ac_ledger_id, b.tran_type, b.tran_method, 'general', b.linked_to,
                    CASE WHEN b.tran_method = 'expense' THEN b.total_amount ELSE 0 END, CASE WHEN b.tran_method = 'income' THEN b.total_amount ELSE 0 END FROM ac_balance_sheets b) l";
    }

    private static function lineTable()
    {
        return DB::table(DB::raw(self::lines()));
    }

    /** The side an opening balance and the balance sit on: debit for cash, bank, asset and expense; credit for income */
    private static function isCredit(string $type): bool
    {
        return $type === 'income';
    }

    /** ledgers with what happened before the period, in it, and up to its end */
    private static function ledgerFigures(string $from, string $to, ?string $type = null, ?int $ledgerId = null): array
    {
        $before = self::lineTable()->where('tran_date', '<', $from)->selectRaw('ledger_id, SUM(debit) as d, SUM(credit) as c')->groupBy('ledger_id')->get()->keyBy('ledger_id');
        $during = self::lineTable()->whereBetween('tran_date', [$from, $to])->selectRaw('ledger_id, SUM(debit) as d, SUM(credit) as c')->groupBy('ledger_id')->get()->keyBy('ledger_id');
        return AcLedger::when($type, fn($q, $v) => $q->where('ledger_type', $v))->when($ledgerId, fn($q, $v) => $q->where('id', $v))->orderBy('ledger_type')->orderBy('serial')->orderBy('name')->get()->map(function ($l) use ($before, $during) {
            $b = $before->get($l->id); $x = $during->get($l->id);
            $sign = self::isCredit($l->ledger_type) ? -1 : 1; // natural side positive
            $opening = round((float) $l->opening_balance + $sign * ((float) ($b->d ?? 0) - (float) ($b->c ?? 0)), 2);
            $debit = round((float) ($x->d ?? 0), 2); $credit = round((float) ($x->c ?? 0), 2);
            return ['id' => $l->id, 'name' => $l->name, 'ledger_type' => $l->ledger_type, 'status' => $l->status, 'opening' => $opening, 'debit' => $debit, 'credit' => $credit, 'closing' => round($opening + $sign * ($debit - $credit), 2)];
        })->all();
    }

    private static function r_trial_balance(array $f): array
    {
        $rows = [];
        foreach (self::ledgerFigures('1900-01-01', $f['date_to']) as $l) {
            $natural = $l['closing']; // positive = on the natural side of the ledger
            $creditNatural = self::isCredit($l['ledger_type']);
            $debit = $creditNatural ? max(-$natural, 0) : max($natural, 0);
            $credit = $creditNatural ? max($natural, 0) : max(-$natural, 0);
            $rows[] = ['name' => $l['name'], 'ledger_type' => ucfirst($l['ledger_type']), 'debit' => round($debit, 2), 'credit' => round($credit, 2)];
        }
        // opening balances are typed in one sided, so the difference is shown as its own line and the two sides agree
        $diff = round(array_sum(array_column($rows, 'debit')) - array_sum(array_column($rows, 'credit')), 2);
        if ($diff != 0) {
            $rows[] = ['name' => 'Opening balances (offset)', 'ledger_type' => 'Equity', 'debit' => $diff < 0 ? abs($diff) : 0, 'credit' => $diff > 0 ? $diff : 0];
        }
        return [$rows, ['name' => ['Ledger', 'text'], 'ledger_type' => ['Type', 'text'], 'debit' => ['Debit', 'money', 1], 'credit' => ['Credit', 'money', 1]]];
    }

    private static function r_ledger_balances(array $f): array
    {
        $rows = array_map(fn($l) => $l + ['type_label' => ucfirst($l['ledger_type'])], self::ledgerFigures($f['date_from'], $f['date_to'], $f['ledger_type']));
        return [$rows, ['name' => ['Ledger', 'text'], 'type_label' => ['Type', 'text'], 'opening' => ['Opening', 'money', 1], 'debit' => ['Debit', 'money', 1], 'credit' => ['Credit', 'money', 1], 'closing' => ['Closing', 'money', 1], 'status' => ['Status', 'badge']]];
    }

    private static function r_ledger_statement(array $f): array
    {
        $ledger = AcLedger::find($f['ledger_id'] ?? 0) ?? AcLedger::orderBy('ledger_type')->orderBy('id')->first();
        $cols = ['tran_date' => ['Date', 'date'], 'ref' => ['Ref', 'text'], 'description' => ['Description', 'text'], 'against' => ['Against', 'text'], 'debit' => ['Debit', 'money', 1], 'credit' => ['Credit', 'money', 1], 'balance' => ['Balance', 'money']];
        if (!$ledger) {
            return [[], $cols];
        }
        $sign = self::isCredit($ledger->ledger_type) ? -1 : 1;
        $fig = self::ledgerFigures($f['date_from'], $f['date_to'], null, $ledger->id)[0];
        $rows = [['tran_date' => $f['date_from'], 'ref' => '', 'description' => 'Opening balance - '.$ledger->name, 'against' => '', 'debit' => 0, 'credit' => 0, 'balance' => $fig['opening']]];
        $bal = $fig['opening'];
        $lines = self::lineTable()->where('ledger_id', $ledger->id)->whereBetween('tran_date', [$f['date_from'], $f['date_to']])->orderBy('tran_date')->orderBy('src')->orderBy('ref_id')->get();
        $cbIds = $lines->where('src', 'cashbook')->pluck('ref_id'); $bsIds = $lines->where('src', 'balance_sheet')->pluck('ref_id');
        $desc = [];
        foreach (DB::table('ac_tran_items')->whereIn('ac_cashbook_id', $cbIds)->get() as $i) { $desc['cashbook'][$i->ac_cashbook_id][] = $i->description; }
        foreach (DB::table('ac_tran_items')->whereIn('ac_balance_sheet_id', $bsIds)->get() as $i) { $desc['balance_sheet'][$i->ac_balance_sheet_id][] = $i->description; }
        $ledgerName = AcLedger::pluck('name', 'id');
        $cbLedger = DB::table('ac_cashbooks')->whereIn('id', $lines->where('src', 'balance_sheet')->pluck('linked_to'))->pluck('ac_ledger_id', 'id');
        $bsLedger = DB::table('ac_balance_sheets')->whereIn('id', $lines->where('src', 'cashbook')->where('entry_kind', 'general')->pluck('linked_to'))->pluck('ac_ledger_id', 'id');
        $otherCb = DB::table('ac_cashbooks')->whereIn('id', $lines->where('src', 'cashbook')->where('entry_kind', 'contra')->pluck('linked_to'))->pluck('ac_ledger_id', 'id');
        foreach ($lines as $l) {
            $bal = round($bal + $sign * ((float) $l->debit - (float) $l->credit), 2);
            $other = $l->src === 'balance_sheet' ? ($cbLedger[$l->linked_to] ?? null) : ($l->entry_kind === 'contra' ? ($otherCb[$l->linked_to] ?? null) : ($bsLedger[$l->linked_to] ?? null));
            $rows[] = ['tran_date' => $l->tran_date, 'ref' => ($l->src === 'cashbook' ? 'CB-' : 'BS-').$l->ref_id, 'description' => implode('; ', $desc[$l->src][$l->ref_id] ?? []), 'against' => $ledgerName[$other] ?? '',
                'debit' => (float) $l->debit, 'credit' => (float) $l->credit, 'balance' => $bal];
        }
        return [$rows, $cols];
    }

    private static function r_day_book(array $f): array
    {
        // GROUP_CONCAT's separator syntax differs between MySQL (production) and SQLite (the test suite's in-memory DB).
        $groupConcat = DB::connection()->getDriverName() === 'sqlite'
            ? "GROUP_CONCAT(i.description, '; ')"
            : "GROUP_CONCAT(i.description SEPARATOR '; ')";
        $rows = DB::table('ac_cashbooks as c')->join('ac_ledgers as l', 'l.id', '=', 'c.ac_ledger_id')
            ->leftJoin('ac_balance_sheets as b', fn($j) => $j->on('b.id', '=', 'c.linked_to')->where('c.entry_kind', 'general'))->leftJoin('ac_ledgers as o', 'o.id', '=', 'b.ac_ledger_id')
            ->whereBetween('c.tran_date', [$f['date_from'], $f['date_to']])->when($f['ledger_type'], fn($q, $v) => $q->where('l.ledger_type', $v))
            ->selectRaw("c.tran_date, c.id, c.entry_kind, c.tran_type, c.tran_method, l.name as ledger, o.name as against, CASE WHEN c.tran_method = 'income' THEN c.total_amount ELSE 0 END as received, CASE WHEN c.tran_method = 'expense' THEN c.total_amount ELSE 0 END as paid,
                (SELECT $groupConcat FROM ac_tran_items i WHERE i.ac_cashbook_id = c.id) as description")
            ->orderBy('c.tran_date')->orderBy('c.id')->get()
            ->map(function ($r) { $r->ref = 'CB-'.$r->id; $r->kind = $r->entry_kind === 'contra' ? 'Transfer' : ($r->tran_method === 'income' ? 'Receipt' : 'Payment'); return $r; })->all();
        return [$rows, ['tran_date' => ['Date', 'date'], 'ref' => ['Ref', 'text'], 'kind' => ['Kind', 'badge'], 'ledger' => ['Cash / bank ledger', 'text'], 'against' => ['Against', 'text'], 'description' => ['Description', 'text'], 'received' => ['Received', 'money', 1], 'paid' => ['Paid', 'money', 1]]];
    }

    private static function r_cash_bank_position(array $f): array
    {
        $rows = [];
        foreach (['cash', 'bank'] as $t) {
            foreach (self::ledgerFigures($f['date_from'], $f['date_to'], $t) as $l) {
                $rows[] = ['name' => $l['name'], 'type_label' => ucfirst($t), 'opening' => $l['opening'], 'received' => $l['debit'], 'paid' => $l['credit'], 'closing' => $l['closing']];
            }
        }
        return [$rows, ['name' => ['Ledger', 'text'], 'type_label' => ['Type', 'text'], 'opening' => ['Opening', 'money', 1], 'received' => ['Received', 'money', 1], 'paid' => ['Paid', 'money', 1], 'closing' => ['Closing', 'money', 1]]];
    }

    private static function r_daily_cash_flow(array $f): array
    {
        $rows = DB::table('ac_cashbooks')->where('entry_kind', 'general')->whereBetween('tran_date', [$f['date_from'], $f['date_to']])
            ->selectRaw("tran_date, SUM(CASE WHEN tran_method = 'income' THEN total_amount ELSE 0 END) as received, SUM(CASE WHEN tran_method = 'expense' THEN total_amount ELSE 0 END) as paid,
                SUM(CASE WHEN tran_method = 'income' THEN total_amount ELSE -total_amount END) as net, COUNT(*) as entries")
            ->groupBy('tran_date')->orderBy('tran_date')->get()->all();
        return [$rows, ['tran_date' => ['Date', 'date'], 'entries' => ['Entries', 'int', 1], 'received' => ['Received', 'money', 1], 'paid' => ['Paid', 'money', 1], 'net' => ['Net', 'money', 1]]];
    }

    private static function r_income_statement(array $f): array
    {
        $rows = [];
        foreach (['income' => 'Income', 'expense' => 'Expense'] as $type => $label) {
            foreach (self::ledgerFigures($f['date_from'], $f['date_to'], $type) as $l) {
                $amount = $type === 'income' ? $l['credit'] - $l['debit'] : $l['debit'] - $l['credit'];
                if ($amount != 0 || $l['opening'] != 0) {
                    $rows[] = ['section' => $label, 'name' => $l['name'], 'amount' => round($amount, 2), 'signed' => $type === 'income' ? round($amount, 2) : round(-$amount, 2)];
                }
            }
        }
        $net = round(array_sum(array_column($rows, 'signed')), 2);
        $rows[] = ['section' => $net >= 0 ? 'Surplus' : 'Deficit', 'name' => 'Income less expense', 'amount' => abs($net), 'signed' => $net];
        return [$rows, ['section' => ['Section', 'badge'], 'name' => ['Ledger', 'text'], 'amount' => ['Amount', 'money']]];
    }
}
