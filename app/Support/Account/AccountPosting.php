<?php

namespace App\Support\Account;

use App\Models\AcBalanceSheet;
use App\Models\AcCashbook;
use App\Models\AcLedger;
use App\Models\AcTranItem;
use Illuminate\Support\Facades\DB;

class AccountPosting
{
    public const COUNTER_TYPES = ['income', 'expense', 'asset'];

    public static function balance(int $ledgerId): float
    {
        $l = AcLedger::find($ledgerId);
        if (!$l) {
            return 0.0;
        }
        $in = (float) DB::table('ac_cashbooks')->where('ac_ledger_id', $ledgerId)->where('tran_method', 'income')->sum('total_amount');
        $out = (float) DB::table('ac_cashbooks')->where('ac_ledger_id', $ledgerId)->where('tran_method', 'expense')->sum('total_amount');
        return round((float) $l->opening_balance + $in - $out, 2);
    }

    private static function items(array $items): array
    {
        $out = [];
        foreach ($items as $i) {
            $i = (array) $i;
            $amount = round((float) ($i['amount'] ?? 0), 2);
            if ($amount > 0) {
                $out[] = ['folio_number' => $i['folio_number'] ?? null, 'description' => trim((string) ($i['description'] ?? '')) ?: 'Transaction', 'amount' => $amount];
            }
        }
        return $out;
    }

    private static function ledger(?int $id, array $types, string $label): array
    {
        $l = $id ? AcLedger::lockForUpdate()->find($id) : null;
        if (!$l) {
            return [null, $label.' ledger not found'];
        }
        if ($l->status !== 'Active') {
            return [null, $label.' ledger "'.$l->name.'" is not active'];
        }
        if (!in_array($l->ledger_type, $types, true)) {
            return [null, $label.' ledger "'.$l->name.'" must be of type '.implode(' / ', $types)];
        }
        return [$l, null];
    }

    public static function post(array $d): array
    {
        $type = $d['tran_type'] ?? '';
        $method = $d['tran_method'] ?? '';
        if (!in_array($type, ['cash', 'bank'], true) || !in_array($method, ['income', 'expense'], true)) {
            return ['ok' => false, 'error' => 'Transaction type must be cash or bank, income or expense'];
        }
        $items = self::items($d['items'] ?? []);
        if (!$items) {
            return ['ok' => false, 'error' => 'Add at least one line with an amount'];
        }
        $total = round(array_sum(array_column($items, 'amount')), 2);
        return DB::transaction(function () use ($d, $type, $method, $items, $total) {
            [$cash, $e1] = self::ledger($d['cash_ledger_id'] ?? null, [$type], ucfirst($type));
            [$counter, $e2] = self::ledger($d['counter_ledger_id'] ?? null, self::COUNTER_TYPES, 'Account');
            if ($e1 || $e2) {
                return ['ok' => false, 'error' => $e1 ?? $e2];
            }
            if ($method === 'expense' && self::balance($cash->id) < $total) {
                return ['ok' => false, 'error' => 'Not enough money in "'.$cash->name.'": it holds '.number_format(self::balance($cash->id), 2).', the payment is '.number_format($total, 2)];
            }
            $date = $d['tran_date'] ?? now()->toDateString();
            $cb = new AcCashbook;
            $cb->tran_date = $date; $cb->tran_type = $type; $cb->tran_method = $method; $cb->ac_ledger_id = $cash->id; $cb->total_amount = $total;
            $cb->entry_kind = 'general'; $cb->source = $d['source'] ?? null; $cb->source_id = $d['source_id'] ?? null;
            $cb->save();
            $bs = new AcBalanceSheet;
            $bs->tran_date = $date; $bs->tran_type = $type; $bs->tran_method = $method; $bs->ac_ledger_id = $counter->id; $bs->total_amount = $total;
            $bs->save();
            $cb->linked_to = $bs->id; $cb->save();
            $bs->linked_to = $cb->id; $bs->save();
            foreach ($items as $i) {
                $t = new AcTranItem;
                $t->ac_balance_sheet_id = $bs->id; $t->ac_cashbook_id = $cb->id; $t->folio_number = $i['folio_number']; $t->description = $i['description']; $t->amount = $i['amount'];
                $t->save();
            }
            return ['ok' => true, 'cashbook_id' => $cb->id, 'balance_sheet_id' => $bs->id, 'amount' => $total];
        });
    }

    public static function contra(array $d): array
    {
        $items = self::items($d['items'] ?? []);
        if (!$items) {
            return ['ok' => false, 'error' => 'Add at least one line with an amount'];
        }
        $total = round(array_sum(array_column($items, 'amount')), 2);
        return DB::transaction(function () use ($d, $items, $total) {
            [$from, $e1] = self::ledger($d['from_ledger_id'] ?? null, ['cash', 'bank'], 'Source');
            [$to, $e2] = self::ledger($d['to_ledger_id'] ?? null, ['cash', 'bank'], 'Destination');
            if ($e1 || $e2) {
                return ['ok' => false, 'error' => $e1 ?? $e2];
            }
            if ($from->id === $to->id || $from->ledger_type === $to->ledger_type) {
                return ['ok' => false, 'error' => 'A deposit or withdrawal moves money between a cash ledger and a bank ledger'];
            }
            if (self::balance($from->id) < $total) {
                return ['ok' => false, 'error' => 'Not enough money in "'.$from->name.'": it holds '.number_format(self::balance($from->id), 2).', the transfer is '.number_format($total, 2)];
            }
            $date = $d['tran_date'] ?? now()->toDateString();
            $ids = [];
            foreach ([[$from, 'expense'], [$to, 'income']] as [$ledger, $method]) {
                $cb = new AcCashbook;
                $cb->tran_date = $date; $cb->tran_type = $ledger->ledger_type; $cb->tran_method = $method; $cb->ac_ledger_id = $ledger->id; $cb->total_amount = $total; $cb->entry_kind = 'contra';
                $cb->save();
                $ids[] = $cb->id;
            }
            AcCashbook::where('id', $ids[0])->update(['linked_to' => $ids[1]]);
            AcCashbook::where('id', $ids[1])->update(['linked_to' => $ids[0]]);
            foreach ($ids as $cbId) {
                foreach ($items as $i) {
                    $t = new AcTranItem;
                    $t->ac_cashbook_id = $cbId; $t->folio_number = $i['folio_number']; $t->description = $i['description']; $t->amount = $i['amount'];
                    $t->save();
                }
            }
            return ['ok' => true, 'cashbook_ids' => $ids, 'amount' => $total];
        });
    }

    public static function reverse(int $cashbookId, string $reason, ?string $date = null): ?int
    {
        $cb = AcCashbook::find($cashbookId);
        if (!$cb || $cb->entry_kind !== 'general') {
            return null;
        }
        $bs = AcBalanceSheet::find($cb->linked_to);
        $method = $cb->tran_method === 'income' ? 'expense' : 'income';
        $r = self::post([
            'tran_type' => $cb->tran_type, 'tran_method' => $method, 'cash_ledger_id' => $cb->ac_ledger_id, 'counter_ledger_id' => $bs?->ac_ledger_id, 'tran_date' => $date ?? now()->toDateString(),
            'items' => [['description' => 'Reversal: '.$reason, 'amount' => $cb->total_amount]], 'source' => 'reversal', 'source_id' => $cb->id,
        ]);
        return $r['ok'] ? $r['cashbook_id'] : null;
    }
}
