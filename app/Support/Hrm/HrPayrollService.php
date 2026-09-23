<?php

namespace App\Support\Hrm;

use App\Models\AdminUser;
use App\Models\HrAdvance;
use App\Models\HrAdvanceRecovery;
use App\Models\HrAttendance;
use App\Models\HrDeduction;
use App\Models\HrPayroll;
use App\Models\HrPayslip;
use App\Models\HrPfEntry;
use App\Models\HrSalaryPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Monthly payroll.
 *
 * generate  makes (or remakes) the draft payslips of a month from the salary setups, attendance, leaves, roster,
 *           holidays, advances and deductions. Nothing outside the draft changes.
 * approve   locks it and takes effect: advances are recovered, deductions are marked taken, provident fund
 *           contributions are written to the fund ledger.
 * reopen    undoes approve while nothing has been paid.
 * pay       records salary payments against a payslip.
 */
class HrPayrollService
{
    private static function r($n): float
    {
        return round((float) $n, 2);
    }

    // ---------------------------------------------------------------------------------------------- one payslip

    /**
     * What one employee earns and loses in a month
     *
     * @return array attributes of the payslip (breakdown included)
     */
    public static function compute(AdminUser $u, Carbon $month): array
    {
        $setup = $u->salarySetup()->with(['items.component'])->first();
        $cfg = HrSettings::all();
        $from = $month->copy()->startOfMonth();
        $to = $month->copy()->endOfMonth()->startOfDay();
        $D = $from->daysInMonth;
        $basis = $cfg['month_days_basis'] === '30' ? 30 : $D;
        $profile = $u->hrProfile;

        // days the person is employed in this month
        $start = $profile?->join_date && Carbon::parse($profile->join_date)->gt($from) ? Carbon::parse($profile->join_date)->startOfDay() : $from->copy();
        $end = $profile?->resign_date && Carbon::parse($profile->resign_date)->lt($to) ? Carbon::parse($profile->resign_date)->startOfDay() : $to->copy();
        $active = $end->gte($start) ? (int) round($start->diffInDays($end)) + 1 : 0;
        $ratio = $D > 0 ? $active / $D : 0;

        $basic = self::r($setup->basic * $ratio);
        $earn = [];
        $dedComp = [];
        foreach ($setup->items as $it) {
            $c = $it->component;
            if (!$c) {
                continue;
            }
            $amt = $c->calc_type === 'Percent' ? $setup->basic * $it->value / 100 : $it->value;
            $amt = self::r($amt * $ratio);
            if ($c->component_type === 'Earning') {
                $earn[] = ['name' => $c->name, 'amount' => $amt];
            } else {
                $dedComp[] = ['name' => $c->name, 'amount' => $amt];
            }
        }
        $totalAllowance = self::r(array_sum(array_column($earn, 'amount')));
        $gross = self::r($basic + $totalAllowance);
        $componentDeduction = self::r(array_sum(array_column($dedComp, 'amount')));
        // a day of pay is the full monthly gross over the month basis
        $fullGross = $setup->basic + array_sum(array_map(fn($i) => $i->component && $i->component->component_type === 'Earning'
            ? ($i->component->calc_type === 'Percent' ? $setup->basic * $i->value / 100 : $i->value) : 0, $setup->items->all()));
        $perDay = $basis > 0 ? $fullGross / $basis : 0;

        // the days of the month
        $roster = HrAttendanceService::rosterMap($from->toDateString(), $to->toDateString(), [$u->id])[$u->id] ?? [];
        $holidays = HrAttendanceService::holidayMap($from->toDateString(), $to->toDateString());
        $leaves = HrAttendanceService::leaveMap($from->toDateString(), $to->toDateString(), [$u->id])[$u->id] ?? [];
        $att = HrAttendance::where('admin_user_id', $u->id)->whereBetween('work_date', [$from->toDateString(), $to->toDateString()])->get()->keyBy(fn($a) => $a->work_date->toDateString());
        $working = $present = $paidLeave = $absent = 0;
        $late = 0;
        $offDays = 0;
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $k = $d->toDateString();
            if (HrAttendanceService::offReason($k, $roster[$k] ?? null, $holidays, $cfg['weekly_off'])) {
                $offDays++;
                continue;
            }
            $working++;
            if (isset($leaves[$k])) {
                $leaves[$k][1] === 'Yes' ? $paidLeave++ : $absent++;
                continue;
            }
            $a = $att[$k] ?? null;
            if (!$a) {
                ($cfg['absent_if_no_record'] === 'Yes') ? $absent++ : $present++;
                continue;
            }
            switch ($a->status) {
                case 'Absent':
                    $absent++;
                    break;
                case 'Half Day':
                    $present += 0.5;
                    $absent += 0.5;
                    break;
                case 'Late':
                    $present++;
                    $late++;
                    break;
                default:
                    $present++;
            }
        }
        $absentDeduction = $setup->deduct_absent === 'Yes' ? self::r($absent * $perDay) : 0;
        $lateDeduction = 0;
        if ($setup->late_fee_applies === 'Yes' && $late > 0) {
            if ($cfg['late_rule'] === 'days' && (int) $cfg['late_count_for_day'] > 0) {
                $lateDeduction = self::r(intdiv($late, (int) $cfg['late_count_for_day']) * $perDay);
            } elseif ($cfg['late_rule'] === 'fixed') {
                $lateDeduction = self::r($late * (float) $cfg['late_fee_per_instance']);
            }
        }
        $pfEmp = self::r($basic * $setup->pf_employee_percent / 100);
        $pfEr = self::r($basic * $setup->pf_employer_percent / 100);

        // advances still to be recovered
        $adv = [];
        foreach (HrAdvance::where('admin_user_id', $u->id)->where('status', 'Approved')->where('balance', '>', 0)->where('advance_date', '<=', $to->toDateString())->orderBy('id')->get() as $a) {
            $adv[] = ['id' => $a->id, 'amount' => self::r(min($a->monthly_deduction, $a->balance)), 'reason' => $a->reason];
        }
        $advanceDeduction = self::r(array_sum(array_column($adv, 'amount')));
        // deductions of the month that no payslip has taken yet
        $oth = [];
        foreach (HrDeduction::where('admin_user_id', $u->id)->whereNull('applied_payslip_id')->whereBetween('deduction_month', [$from->toDateString(), $to->toDateString()])->orderBy('id')->get() as $x) {
            $oth[] = ['id' => $x->id, 'type' => $x->deduction_type, 'amount' => self::r($x->amount), 'reason' => $x->reason];
        }
        $otherDeduction = self::r(array_sum(array_column($oth, 'amount')));

        $totalDeduction = self::r($absentDeduction + $lateDeduction + $pfEmp + $componentDeduction + $advanceDeduction + $otherDeduction);
        $net = max(self::r($gross - $totalDeduction), 0);
        return [
            'admin_user_id' => $u->id, 'basic' => $basic, 'total_allowance' => $totalAllowance, 'gross' => $gross,
            'days_in_month' => $D, 'working_days' => $working, 'present_days' => $present, 'paid_leave_days' => $paidLeave, 'absent_days' => $absent, 'late_count' => $late,
            'absent_deduction' => $absentDeduction, 'late_deduction' => $lateDeduction, 'pf_employee' => $pfEmp, 'pf_employer' => $pfEr,
            'component_deduction' => $componentDeduction, 'advance_deduction' => $advanceDeduction, 'other_deduction' => $otherDeduction,
            'total_deduction' => $totalDeduction, 'net' => $net, 'paid_amount' => 0, 'status' => 'Unpaid',
            'breakdown' => [
                'allowances' => $earn, 'deduction_components' => $dedComp, 'advances' => $adv, 'deductions' => $oth,
                'per_day' => self::r($perDay), 'basis_days' => $basis, 'employed_days' => $active, 'off_days' => $offDays,
                'shortfall' => max(self::r($totalDeduction - $gross), 0),
                'setup' => ['deduct_absent' => $setup->deduct_absent, 'late_fee_applies' => $setup->late_fee_applies, 'late_rule' => $cfg['late_rule']],
            ],
        ];
    }

    // ---------------------------------------------------------------------------------------------- payroll of a month

    /** Employees who get a payslip in a month: active, with a salary setup, and not gone before the month */
    public static function employees(Carbon $month)
    {
        return AdminUser::with(['hrProfile', 'role:id,name'])->where('status', 'Active')->whereHas('salarySetup', fn($q) => $q->where('basic', '>', 0))
            ->orderBy('name')->get()->filter(function ($u) use ($month) {
                $p = $u->hrProfile;
                if ($p && $p->resign_date && Carbon::parse($p->resign_date)->lt($month->copy()->startOfMonth())) {
                    return false;
                }
                if ($p && $p->join_date && Carbon::parse($p->join_date)->gt($month->copy()->endOfMonth())) {
                    return false;
                }
                return true;
            })->values();
    }

    private static function totals(HrPayroll $p): void
    {
        $s = $p->payslips()->get();
        $p->update([
            'employees' => $s->count(), 'total_gross' => self::r($s->sum('gross')), 'total_deduction' => self::r($s->sum('total_deduction')),
            'total_net' => self::r($s->sum('net')), 'total_paid' => self::r($s->sum('paid_amount')),
        ]);
    }

    /**
     * Make or remake the draft payroll of a month (yyyy-mm)
     *
     * @return array{0: ?HrPayroll, 1: ?string}
     */
    public static function generate(string $ym): array
    {
        $month = Carbon::parse($ym.'-01')->startOfMonth();
        if ($month->gt(now()->endOfMonth())) {
            return [null, 'Payroll cannot be made for a month that has not started'];
        }
        $users = self::employees($month);
        if ($users->isEmpty()) {
            return [null, 'No employee has a salary setup for this month'];
        }
        return DB::transaction(function () use ($month, $users) {
            $p = HrPayroll::where('pay_month', $month->toDateString())->lockForUpdate()->first();
            if ($p && $p->status !== 'Draft') {
                return [null, 'This payroll is already '.strtolower($p->status).'. Reopen it to make it again'];
            }
            if (!$p) {
                $p = HrPayroll::create(['pay_month' => $month->toDateString(), 'title' => 'Salary '.$month->format('F Y'), 'status' => 'Draft', 'generated_by' => HrAttendanceService::actor()]);
            }
            $p->payslips()->delete();
            foreach ($users as $u) {
                $p->payslips()->create(self::compute($u, $month));
            }
            self::totals($p);
            return [$p->fresh(), null];
        });
    }

    /**
     * Lock the payroll and let it take effect
     */
    public static function approve(HrPayroll $payroll): ?string
    {
        return DB::transaction(function () use ($payroll) {
            $p = HrPayroll::lockForUpdate()->find($payroll->id);
            if ($p->status !== 'Draft') {
                return 'Only a draft payroll can be approved';
            }
            $slips = $p->payslips()->get();
            if ($slips->isEmpty()) {
                return 'The payroll has no payslip';
            }
            $date = $p->pay_month->copy()->endOfMonth()->toDateString();
            foreach ($slips as $s) {
                $bd = $s->breakdown ?? [];
                $advTotal = 0;
                foreach ($bd['advances'] ?? [] as $row) {
                    $a = HrAdvance::lockForUpdate()->find($row['id']);
                    if (!$a || $a->status !== 'Approved') {
                        continue;
                    }
                    $amt = self::r(min($row['amount'], $a->balance));
                    if ($amt <= 0) {
                        continue;
                    }
                    HrAdvanceRecovery::create(['hr_advance_id' => $a->id, 'hr_payslip_id' => $s->id, 'amount' => $amt, 'recovered_on' => $date]);
                    $a->balance = self::r($a->balance - $amt);
                    if ($a->balance <= 0) {
                        $a->status = 'Settled';
                    }
                    $a->save();
                    $advTotal += $amt;
                }
                foreach ($bd['deductions'] ?? [] as $row) {
                    HrDeduction::where('id', $row['id'])->whereNull('applied_payslip_id')->update(['applied_payslip_id' => $s->id]);
                }
                if ($s->pf_employee + $s->pf_employer > 0) {
                    HrPfEntry::create(['admin_user_id' => $s->admin_user_id, 'entry_date' => $date, 'entry_type' => 'Contribution', 'employee_amount' => $s->pf_employee,
                        'employer_amount' => $s->pf_employer, 'amount' => self::r($s->pf_employee + $s->pf_employer), 'hr_payslip_id' => $s->id, 'note' => $p->title, 'created_by' => HrAttendanceService::actor()]);
                }
                if (self::r($advTotal) !== self::r($s->advance_deduction)) {
                    // an advance changed after the draft was made: the payslip follows what was really recovered
                    $diff = self::r($s->advance_deduction - $advTotal);
                    $s->update(['advance_deduction' => self::r($advTotal), 'total_deduction' => self::r($s->total_deduction - $diff), 'net' => max(self::r($s->gross - ($s->total_deduction - $diff)), 0)]);
                }
            }
            $p->update(['status' => 'Approved', 'approved_by' => HrAttendanceService::actor(), 'approved_at' => now()]);
            self::totals($p);
            return null;
        });
    }

    /**
     * Take an approved payroll back to draft (only while no salary has been paid)
     */
    public static function reopen(HrPayroll $payroll): ?string
    {
        return DB::transaction(function () use ($payroll) {
            $p = HrPayroll::lockForUpdate()->find($payroll->id);
            if ($p->status === 'Draft') {
                return 'The payroll is already a draft';
            }
            if ($p->payslips()->where('paid_amount', '>', 0)->exists()) {
                return 'Salary is already paid on this payroll and it cannot be reopened';
            }
            $ids = $p->payslips()->pluck('id');
            foreach (HrAdvanceRecovery::whereIn('hr_payslip_id', $ids)->get() as $rec) {
                $a = HrAdvance::find($rec->hr_advance_id);
                if ($a) {
                    $a->balance = self::r($a->balance + $rec->amount);
                    $a->status = 'Approved';
                    $a->save();
                }
                $rec->delete();
            }
            HrDeduction::whereIn('applied_payslip_id', $ids)->update(['applied_payslip_id' => null]);
            HrPfEntry::whereIn('hr_payslip_id', $ids)->delete();
            $p->update(['status' => 'Draft', 'approved_by' => null, 'approved_at' => null]);
            return null;
        });
    }

    /**
     * Pay (part of) a payslip
     */
    public static function pay(HrPayslip $slip, float $amount, string $method, string $date, ?string $reference = null, ?string $note = null): ?string
    {
        return DB::transaction(function () use ($slip, $amount, $method, $date, $reference, $note) {
            $s = HrPayslip::lockForUpdate()->find($slip->id);
            $p = $s->payroll;
            if (!in_array($p->status, ['Approved', 'Partly Paid', 'Paid'], true)) {
                return 'Approve the payroll before paying salary';
            }
            $due = self::r($s->net - $s->paid_amount);
            $amount = self::r($amount);
            if ($amount <= 0) {
                return 'Enter the amount to pay';
            }
            if ($amount > $due) {
                return 'The amount is more than the salary due ('.number_format($due, 2).')';
            }
            HrSalaryPayment::create(['hr_payslip_id' => $s->id, 'admin_user_id' => $s->admin_user_id, 'amount' => $amount, 'method' => $method, 'paid_on' => $date,
                'reference' => $reference, 'note' => $note, 'paid_by' => HrAttendanceService::actor()]);
            $paid = self::r($s->paid_amount + $amount);
            $s->update(['paid_amount' => $paid, 'status' => $paid >= $s->net ? 'Paid' : 'Partial']);
            self::totals($p);
            $p->refresh();
            $p->update(['status' => $p->payslips()->where('status', '!=', 'Paid')->exists() ? 'Partly Paid' : 'Paid']);
            return null;
        });
    }

    /**
     * Pay every unpaid payslip of the payroll in full
     *
     * @return array{0: int, 1: ?string} payslips paid, error
     */
    public static function payAll(HrPayroll $payroll, string $method, string $date): array
    {
        $n = 0;
        foreach ($payroll->payslips()->where('status', '!=', 'Paid')->get() as $s) {
            $due = self::r($s->net - $s->paid_amount);
            if ($due <= 0) {
                $s->update(['status' => 'Paid']);
                continue;
            }
            $err = self::pay($s, $due, $method, $date, null, 'Paid with the payroll');
            if ($err) {
                return [$n, $err];
            }
            $n++;
        }
        return [$n, null];
    }
}
