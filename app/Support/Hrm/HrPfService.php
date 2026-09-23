<?php

namespace App\Support\Hrm;

use App\Models\HrPfEntry;
use Illuminate\Support\Facades\DB;

/**
 * Provident fund of an employee: a ledger of contributions (from payroll), opening balance, interest and withdrawals.
 */
class HrPfService
{
    public static function balance(int $userId): float
    {
        return round((float) HrPfEntry::where('admin_user_id', $userId)->sum('amount'), 2);
    }

    /** Ledger with the running balance */
    public static function ledger(int $userId)
    {
        $run = 0;
        return HrPfEntry::where('admin_user_id', $userId)->orderBy('entry_date')->orderBy('id')->get()->map(function ($e) use (&$run) {
            $run = round($run + $e->amount, 2);
            $e->balance = $run;
            return $e;
        });
    }

    /**
     * Opening balance, interest or withdrawal typed by hand
     *
     * @return string|null why it cannot be done
     */
    public static function add(int $userId, string $type, float $amount, string $date, ?string $note = null): ?string
    {
        if (!in_array($type, ['Opening', 'Interest', 'Withdrawal'], true)) {
            return 'Unknown entry type';
        }
        if ($amount <= 0) {
            return 'Enter an amount';
        }
        return DB::transaction(function () use ($userId, $type, $amount, $date, $note) {
            // the employee row is locked, so two withdrawals cannot both pass the balance check
            \App\Models\AdminUser::lockForUpdate()->find($userId);
            if ($type === 'Withdrawal' && $amount > self::balance($userId)) {
                return 'The withdrawal is more than the fund balance ('.number_format(self::balance($userId), 2).')';
            }
            HrPfEntry::create([
                'admin_user_id' => $userId, 'entry_date' => $date, 'entry_type' => $type,
                'employee_amount' => $type === 'Withdrawal' ? 0 : ($type === 'Opening' ? $amount : 0), 'employer_amount' => 0,
                'amount' => $type === 'Withdrawal' ? -$amount : $amount, 'note' => $note, 'created_by' => HrAttendanceService::actor(),
            ]);
            return null;
        });
    }
}
