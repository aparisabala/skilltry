<?php

namespace App\Support\Hrm;

use App\Models\AdminUser;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * HR reports, one engine for the web pages and the API.
 *
 * run($key, $filters) answers: title, filters used, columns (field => label, type, total), rows and totals.
 * Column types: text, int, money, date, datetime, badge.
 * Filters: date_from, date_to, month (yyyy-mm), department_id, status, user_id, leave_type_id.
 */
class HrReports
{
    public const MAX_ROWS = 5000;

    /** key => [group, title, description, filters that apply] */
    public const CATALOGUE = [
        'employee_directory' => ['Employees', 'Employee directory', 'Every active employee with department, job title, type, status, joining date and contact.', ['department', 'status']],
        'headcount' => ['Employees', 'Headcount by department', 'Number of employees in each department by employment type.', []],
        'joining_leaving' => ['Employees', 'Joining and leaving', 'Employees who joined or left in the period.', ['date']],
        'attendance_sheet' => ['Attendance', 'Monthly attendance sheet', 'Every employee by day of the month (P present, L late, A absent, H half day, V leave, O off).', ['month', 'department']],
        'attendance_summary' => ['Attendance', 'Attendance summary', 'Present, late, absent, half day, leave and worked hours of each employee for a month.', ['month', 'department']],
        'late_report' => ['Attendance', 'Late arrivals', 'Every late check in of the period with the minutes late.', ['date', 'department']],
        'absent_report' => ['Attendance', 'Absent days', 'Every absent day of the period.', ['date', 'department']],
        'roster_report' => ['Roster', 'Duty roster', 'The shift of every employee on every day of the period (up to 31 days).', ['date', 'department']],
        'leave_register' => ['Leave', 'Leave register', 'Leaves of the period with type, days and status.', ['date', 'status', 'department', 'leave_type']],
        'leave_balance' => ['Leave', 'Leave balance', 'Approved days taken against the days a year of each leave type (calendar year of the from date).', ['date', 'department']],
        'payroll_register' => ['Payroll', 'Payroll register', 'The payslips of a month: gross, every deduction, net, paid and due.', ['month', 'department']],
        'salary_by_department' => ['Payroll', 'Salary by department', 'Gross, deductions and net of a month per department.', ['month']],
        'salary_payments' => ['Payroll', 'Salary payments', 'Salary paid in the period with method and reference.', ['date']],
        'advance_outstanding' => ['Payroll', 'Advances outstanding', 'Approved advances that are not fully recovered.', ['department']],
        'deduction_report' => ['Payroll', 'Deductions', 'Deductions of the period, taken in a payslip or waiting.', ['date', 'department']],
        'pf_balance' => ['Provident fund', 'Provident fund balance', 'Contributions, opening balance, interest, withdrawals and the balance of each employee.', ['department']],
    ];

    public static function catalogue(): array
    {
        $out = [];
        foreach (self::CATALOGUE as $key => [$group, $title, $desc, $filters]) {
            $out[] = ['key' => $key, 'group' => $group, 'title' => $title, 'description' => $desc, 'filters' => $filters];
        }
        return $out;
    }

    public static function filters(array $in): array
    {
        return [
            'date_from' => !empty($in['date_from']) ? Carbon::parse($in['date_from'])->toDateString() : now()->startOfMonth()->toDateString(),
            'date_to' => !empty($in['date_to']) ? Carbon::parse($in['date_to'])->toDateString() : now()->toDateString(),
            'month' => !empty($in['month']) ? Carbon::parse($in['month'].'-01')->format('Y-m') : now()->format('Y-m'),
            'department_id' => !empty($in['department_id']) ? (int) $in['department_id'] : null,
            'status' => !empty($in['status']) ? (string) $in['status'] : null,
            'user_id' => !empty($in['user_id']) ? (int) $in['user_id'] : null,
            'leave_type_id' => !empty($in['leave_type_id']) ? (int) $in['leave_type_id'] : null,
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
            'key' => $key, 'title' => self::CATALOGUE[$key][1], 'description' => self::CATALOGUE[$key][2], 'group' => self::CATALOGUE[$key][0],
            'filters' => $f,
            'columns' => collect($columns)->map(fn($c) => ['label' => $c[0], 'type' => $c[1], 'total' => !empty($c[2])])->all(),
            'rows' => $rows, 'totals' => $totals, 'count' => count($rows),
        ];
    }

    // ---------------------------------------------------------------------------------------------- helpers

    /** active employees, with department and profile columns joined */
    private static function staff(array $f, bool $activeOnly = true)
    {
        return DB::table('admin_users as u')
            ->leftJoin('hr_profiles as p', 'p.admin_user_id', '=', 'u.id')
            ->leftJoin('lib_departments as d', 'd.id', '=', 'p.lib_department_id')
            ->leftJoin('admin_user_roles as r', 'r.id', '=', 'u.admin_user_role_id')
            ->when($activeOnly, fn($q) => $q->where('u.status', 'Active'))
            ->when($f['department_id'], fn($q, $v) => $q->where('p.lib_department_id', $v))
            ->when($f['user_id'], fn($q, $v) => $q->where('u.id', $v));
    }

    private static function monthRange(array $f): array
    {
        $from = Carbon::parse($f['month'].'-01');
        return [$from->toDateString(), $from->copy()->endOfMonth()->toDateString(), $from];
    }

    // ---------------------------------------------------------------------------------------------- employees

    private static function r_employee_directory(array $f): array
    {
        $rows = self::staff($f)->when($f['status'], fn($q, $v) => $q->where('p.employee_status', $v))
            ->select('u.id', 'u.name', 'r.name as role', 'd.name as department', 'p.designation_title', 'p.employment_type', 'p.employee_status', 'p.join_date', 'u.mobile_number', 'u.email')
            ->orderBy('u.name')->get()->all();
        return [$rows, ['name' => ['Name', 'text'], 'role' => ['Role', 'text'], 'department' => ['Department', 'text'], 'designation_title' => ['Job title', 'text'], 'employment_type' => ['Type', 'text'],
            'employee_status' => ['Status', 'badge'], 'join_date' => ['Joined', 'date'], 'mobile_number' => ['Mobile', 'text'], 'email' => ['Email', 'text']]];
    }

    private static function r_headcount(array $f): array
    {
        $rows = self::staff($f)->selectRaw("COALESCE(d.name, 'No department') as department, COUNT(*) as total,
            SUM(CASE WHEN COALESCE(p.employment_type,'Permanent') = 'Permanent' THEN 1 ELSE 0 END) as permanent,
            SUM(CASE WHEN p.employment_type = 'Contract' THEN 1 ELSE 0 END) as contract,
            SUM(CASE WHEN p.employment_type = 'Part-time' THEN 1 ELSE 0 END) as part_time,
            SUM(CASE WHEN p.employment_type IN ('Probation','Intern') THEN 1 ELSE 0 END) as probation")
            ->groupBy(DB::raw("COALESCE(d.name, 'No department')"))->orderByDesc('total')->get()->all();
        return [$rows, ['department' => ['Department', 'text'], 'total' => ['Employees', 'int', 1], 'permanent' => ['Permanent', 'int', 1], 'contract' => ['Contract', 'int', 1], 'part_time' => ['Part-time', 'int', 1], 'probation' => ['Probation / Intern', 'int', 1]]];
    }

    private static function r_joining_leaving(array $f): array
    {
        $rows = [];
        foreach (self::staff($f, false)->whereBetween('p.join_date', [$f['date_from'], $f['date_to']])->select('u.name', 'd.name as department', 'p.designation_title', 'p.join_date as day')->get() as $r) {
            $rows[] = ['day' => $r->day, 'event' => 'Joined', 'name' => $r->name, 'department' => $r->department, 'designation_title' => $r->designation_title];
        }
        foreach (self::staff($f, false)->whereBetween('p.resign_date', [$f['date_from'], $f['date_to']])->select('u.name', 'd.name as department', 'p.designation_title', 'p.resign_date as day', 'p.employee_status')->get() as $r) {
            $rows[] = ['day' => $r->day, 'event' => $r->employee_status && $r->employee_status !== 'Active' ? $r->employee_status : 'Left', 'name' => $r->name, 'department' => $r->department, 'designation_title' => $r->designation_title];
        }
        usort($rows, fn($a, $b) => strcmp($a['day'], $b['day']));
        return [$rows, ['day' => ['Date', 'date'], 'event' => ['Event', 'badge'], 'name' => ['Employee', 'text'], 'department' => ['Department', 'text'], 'designation_title' => ['Job title', 'text']]];
    }

    // ---------------------------------------------------------------------------------------------- attendance

    private static function r_attendance_sheet(array $f): array
    {
        [$from, $to, $start] = self::monthRange($f);
        $users = self::staff($f)->select('u.id', 'u.name', 'd.name as department')->orderBy('u.name')->get();
        $ids = $users->pluck('id')->all();
        $att = DB::table('hr_attendances')->whereBetween('work_date', [$from, $to])->whereIn('admin_user_id', $ids)->get()->groupBy('admin_user_id');
        $roster = HrAttendanceService::rosterMap($from, $to, $ids);
        $leaves = HrAttendanceService::leaveMap($from, $to, $ids);
        $holidays = HrAttendanceService::holidayMap($from, $to);
        $off = HrSettings::get('weekly_off');
        $codes = ['Present' => 'P', 'Late' => 'L', 'Absent' => 'A', 'Half Day' => 'H'];
        $cols = ['name' => ['Employee', 'text'], 'department' => ['Department', 'text']];
        for ($d = $start->copy(); $d->lte(Carbon::parse($to)); $d->addDay()) {
            $cols['d'.$d->format('d')] = [$d->format('d').' '.substr($d->format('D'), 0, 2), 'text'];
        }
        $cols += ['present' => ['Present', 'int', 1], 'late' => ['Late', 'int', 1], 'absent' => ['Absent', 'int', 1], 'leave' => ['Leave', 'int', 1]];
        $rows = [];
        foreach ($users as $u) {
            $byDate = ($att[$u->id] ?? collect())->keyBy(fn($a) => Carbon::parse($a->work_date)->toDateString());
            $row = ['name' => $u->name, 'department' => $u->department, 'present' => 0, 'late' => 0, 'absent' => 0, 'leave' => 0];
            for ($d = $start->copy(); $d->lte(Carbon::parse($to)); $d->addDay()) {
                $k = $d->toDateString();
                $a = $byDate[$k] ?? null;
                if ($a) {
                    $code = $codes[$a->status] ?? '?';
                    $row['d'.$d->format('d')] = $code;
                    $row['present'] += in_array($a->status, ['Present', 'Late'], true) ? 1 : 0;
                    $row['late'] += $a->status === 'Late' ? 1 : 0;
                    $row['absent'] += $a->status === 'Absent' ? 1 : 0;
                } elseif (isset($leaves[$u->id][$k])) {
                    $row['d'.$d->format('d')] = 'V';
                    $row['leave']++;
                } elseif (HrAttendanceService::offReason($k, $roster[$u->id][$k] ?? null, $holidays, $off)) {
                    $row['d'.$d->format('d')] = 'O';
                } else {
                    $row['d'.$d->format('d')] = '';
                }
            }
            $rows[] = $row;
        }
        return [$rows, $cols];
    }

    private static function r_attendance_summary(array $f): array
    {
        [$from, $to] = self::monthRange($f);
        $users = self::staff($f)->select('u.id', 'u.name', 'd.name as department')->orderBy('u.name')->get();
        $rows = [];
        foreach ($users as $u) {
            $m = HrAttendanceService::month($u->id, $f['month'])['summary'];
            $rows[] = ['name' => $u->name, 'department' => $u->department, 'present' => $m['present'], 'late' => $m['late'], 'absent' => $m['absent'], 'half_day' => $m['half_day'], 'leave' => $m['leave'],
                'late_minutes' => $m['late_minutes'], 'worked_hours' => round($m['worked_minutes'] / 60, 1)];
        }
        return [$rows, ['name' => ['Employee', 'text'], 'department' => ['Department', 'text'], 'present' => ['Present days', 'int', 1], 'late' => ['Late days', 'int', 1], 'absent' => ['Absent', 'int', 1], 'half_day' => ['Half days', 'int', 1],
            'leave' => ['Leave', 'int', 1], 'late_minutes' => ['Late minutes', 'int', 1], 'worked_hours' => ['Worked hours', 'int', 1]]];
    }

    private static function attendanceRows(array $f, array $statuses)
    {
        return DB::table('hr_attendances as a')->join('admin_users as u', 'u.id', '=', 'a.admin_user_id')
            ->leftJoin('hr_profiles as p', 'p.admin_user_id', '=', 'u.id')->leftJoin('lib_departments as d', 'd.id', '=', 'p.lib_department_id')
            ->leftJoin('lib_shifts as s', 's.id', '=', 'a.lib_shift_id')
            ->whereBetween('a.work_date', [$f['date_from'], $f['date_to']])->whereIn('a.status', $statuses)
            ->when($f['department_id'], fn($q, $v) => $q->where('p.lib_department_id', $v))->when($f['user_id'], fn($q, $v) => $q->where('a.admin_user_id', $v))
            ->orderBy('a.work_date')->orderBy('u.name');
    }

    private static function r_late_report(array $f): array
    {
        $rows = self::attendanceRows($f, ['Late'])->select('a.work_date', 'u.name', 'd.name as department', 's.name as shift', 'a.check_in', 'a.late_minutes')->get()
            ->map(fn($r) => (array) $r + ['in_time' => $r->check_in ? Carbon::parse($r->check_in)->format('h:i A') : null])->all();
        return [$rows, ['work_date' => ['Date', 'date'], 'name' => ['Employee', 'text'], 'department' => ['Department', 'text'], 'shift' => ['Shift', 'text'], 'in_time' => ['Check in', 'text'], 'late_minutes' => ['Minutes late', 'int', 1]]];
    }

    private static function r_absent_report(array $f): array
    {
        $rows = self::attendanceRows($f, ['Absent'])->select('a.work_date', 'u.name', 'd.name as department', 'a.note')->get()->all();
        return [$rows, ['work_date' => ['Date', 'date'], 'name' => ['Employee', 'text'], 'department' => ['Department', 'text'], 'note' => ['Note', 'text']]];
    }

    private static function r_roster_report(array $f): array
    {
        $from = Carbon::parse($f['date_from']);
        $to = Carbon::parse($f['date_to']);
        if ($from->diffInDays($to) > 30) {
            $to = $from->copy()->addDays(30);
        }
        $users = self::staff($f)->select('u.id', 'u.name', 'd.name as department')->orderBy('u.name')->get();
        $roster = HrAttendanceService::rosterMap($from->toDateString(), $to->toDateString(), $users->pluck('id')->all());
        $shifts = DB::table('lib_shifts')->pluck('name', 'id');
        $cols = ['name' => ['Employee', 'text'], 'department' => ['Department', 'text']];
        for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
            $cols['d'.$d->format('md')] = [$d->format('d M').' '.substr($d->format('D'), 0, 2), 'text'];
        }
        $rows = [];
        foreach ($users as $u) {
            $row = ['name' => $u->name, 'department' => $u->department];
            for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                $r = $roster[$u->id][$d->toDateString()] ?? null;
                $row['d'.$d->format('md')] = $r ? ($r->lib_shift_id ? ($shifts[$r->lib_shift_id] ?? 'Shift') : 'OFF') : '';
            }
            $rows[] = $row;
        }
        return [$rows, $cols];
    }

    // ---------------------------------------------------------------------------------------------- leave

    private static function r_leave_register(array $f): array
    {
        $rows = DB::table('hr_leaves as l')->join('admin_users as u', 'u.id', '=', 'l.admin_user_id')->leftJoin('hr_leave_types as t', 't.id', '=', 'l.hr_leave_type_id')
            ->leftJoin('hr_profiles as p', 'p.admin_user_id', '=', 'u.id')->leftJoin('lib_departments as d', 'd.id', '=', 'p.lib_department_id')
            ->where('l.from_date', '<=', $f['date_to'])->where('l.to_date', '>=', $f['date_from'])
            ->when($f['status'], fn($q, $v) => $q->where('l.status', $v))->when($f['leave_type_id'], fn($q, $v) => $q->where('l.hr_leave_type_id', $v))
            ->when($f['department_id'], fn($q, $v) => $q->where('p.lib_department_id', $v))
            ->select('u.name', 'd.name as department', 't.name as leave_type', 'l.from_date', 'l.to_date', 'l.days', 'l.status', 'l.reason')->orderByDesc('l.from_date')->get()->all();
        return [$rows, ['name' => ['Employee', 'text'], 'department' => ['Department', 'text'], 'leave_type' => ['Leave type', 'text'], 'from_date' => ['From', 'date'], 'to_date' => ['To', 'date'], 'days' => ['Days', 'int', 1], 'status' => ['Status', 'badge'], 'reason' => ['Reason', 'text']]];
    }

    private static function r_leave_balance(array $f): array
    {
        $year = Carbon::parse($f['date_from'])->year;
        $types = DB::table('hr_leave_types')->where('status', 'Active')->orderBy('name')->get();
        $users = self::staff($f)->select('u.id', 'u.name', 'd.name as department')->orderBy('u.name')->get();
        $taken = DB::table('hr_leaves')->where('status', 'Approved')->whereYear('from_date', $year)->selectRaw('admin_user_id, hr_leave_type_id, SUM(days) as d')->groupBy('admin_user_id', 'hr_leave_type_id')->get();
        $cols = ['name' => ['Employee', 'text'], 'department' => ['Department', 'text']];
        foreach ($types as $t) {
            $cols['t'.$t->id] = [$t->name.($t->days_per_year ? ' (of '.$t->days_per_year.')' : ''), 'int', 1];
        }
        $rows = [];
        foreach ($users as $u) {
            $row = ['name' => $u->name, 'department' => $u->department];
            foreach ($types as $t) {
                $row['t'.$t->id] = (float) ($taken->first(fn($x) => $x->admin_user_id == $u->id && $x->hr_leave_type_id == $t->id)->d ?? 0);
            }
            $rows[] = $row;
        }
        return [$rows, $cols];
    }

    // ---------------------------------------------------------------------------------------------- payroll

    private static function slips(array $f)
    {
        [$from] = self::monthRange($f);
        return DB::table('hr_payslips as s')->join('hr_payrolls as pr', 'pr.id', '=', 's.hr_payroll_id')->join('admin_users as u', 'u.id', '=', 's.admin_user_id')
            ->leftJoin('hr_profiles as p', 'p.admin_user_id', '=', 'u.id')->leftJoin('lib_departments as d', 'd.id', '=', 'p.lib_department_id')
            ->where('pr.pay_month', $from)->when($f['department_id'], fn($q, $v) => $q->where('p.lib_department_id', $v));
    }

    private static function r_payroll_register(array $f): array
    {
        $rows = self::slips($f)->select('u.name', 'd.name as department', 's.basic', 's.total_allowance', 's.gross', 's.absent_deduction', 's.late_deduction', 's.pf_employee', 's.component_deduction',
            's.advance_deduction', 's.other_deduction', 's.total_deduction', 's.net', 's.paid_amount', 's.status')->orderBy('u.name')->get()
            ->map(fn($r) => (array) $r + ['due' => round($r->net - $r->paid_amount, 2)])->all();
        return [$rows, ['name' => ['Employee', 'text'], 'department' => ['Department', 'text'], 'basic' => ['Basic', 'money', 1], 'total_allowance' => ['Allowances', 'money', 1], 'gross' => ['Gross', 'money', 1],
            'absent_deduction' => ['Absent', 'money', 1], 'late_deduction' => ['Late fee', 'money', 1], 'pf_employee' => ['Provident fund', 'money', 1], 'component_deduction' => ['Other components', 'money', 1],
            'advance_deduction' => ['Advance', 'money', 1], 'other_deduction' => ['Deductions', 'money', 1], 'total_deduction' => ['Total deduction', 'money', 1], 'net' => ['Net salary', 'money', 1],
            'paid_amount' => ['Paid', 'money', 1], 'due' => ['Due', 'money', 1], 'status' => ['Status', 'badge']]];
    }

    private static function r_salary_by_department(array $f): array
    {
        $rows = self::slips($f)->selectRaw("COALESCE(d.name, 'No department') as department, COUNT(*) as employees, SUM(s.gross) as gross, SUM(s.total_deduction) as total_deduction, SUM(s.net) as net, SUM(s.paid_amount) as paid")
            ->groupBy(DB::raw("COALESCE(d.name, 'No department')"))->orderByDesc('net')->get()->map(fn($r) => (array) $r + ['due' => round($r->net - $r->paid, 2)])->all();
        return [$rows, ['department' => ['Department', 'text'], 'employees' => ['Employees', 'int', 1], 'gross' => ['Gross', 'money', 1], 'total_deduction' => ['Deductions', 'money', 1], 'net' => ['Net salary', 'money', 1], 'paid' => ['Paid', 'money', 1], 'due' => ['Due', 'money', 1]]];
    }

    private static function r_salary_payments(array $f): array
    {
        $rows = DB::table('hr_salary_payments as x')->join('admin_users as u', 'u.id', '=', 'x.admin_user_id')->join('hr_payslips as s', 's.id', '=', 'x.hr_payslip_id')->join('hr_payrolls as pr', 'pr.id', '=', 's.hr_payroll_id')
            ->leftJoin('admin_users as b', 'b.id', '=', 'x.paid_by')->whereBetween('x.paid_on', [$f['date_from'], $f['date_to']])
            ->select('x.paid_on', 'u.name', 'pr.title', 'x.amount', 'x.method', 'x.reference', 'b.name as paid_by')->orderBy('x.paid_on')->orderBy('x.id')->get()->all();
        return [$rows, ['paid_on' => ['Paid on', 'date'], 'name' => ['Employee', 'text'], 'title' => ['Payroll', 'text'], 'amount' => ['Amount', 'money', 1], 'method' => ['Method', 'text'], 'reference' => ['Reference', 'text'], 'paid_by' => ['Paid by', 'text']]];
    }

    private static function r_advance_outstanding(array $f): array
    {
        $rows = DB::table('hr_advances as a')->join('admin_users as u', 'u.id', '=', 'a.admin_user_id')->leftJoin('hr_profiles as p', 'p.admin_user_id', '=', 'u.id')->leftJoin('lib_departments as d', 'd.id', '=', 'p.lib_department_id')
            ->where('a.status', 'Approved')->where('a.balance', '>', 0)->when($f['department_id'], fn($q, $v) => $q->where('p.lib_department_id', $v))
            ->select('u.name', 'd.name as department', 'a.advance_date', 'a.amount', 'a.monthly_deduction', 'a.balance', 'a.reason')->orderByDesc('a.balance')->get()
            ->map(fn($r) => (array) $r + ['recovered' => round($r->amount - $r->balance, 2)])->all();
        return [$rows, ['name' => ['Employee', 'text'], 'department' => ['Department', 'text'], 'advance_date' => ['Date', 'date'], 'amount' => ['Advance', 'money', 1], 'recovered' => ['Recovered', 'money', 1], 'balance' => ['Balance', 'money', 1], 'monthly_deduction' => ['Monthly', 'money'], 'reason' => ['Reason', 'text']]];
    }

    private static function r_deduction_report(array $f): array
    {
        $rows = DB::table('hr_deductions as x')->join('admin_users as u', 'u.id', '=', 'x.admin_user_id')->leftJoin('hr_profiles as p', 'p.admin_user_id', '=', 'u.id')->leftJoin('lib_departments as d', 'd.id', '=', 'p.lib_department_id')
            ->whereBetween('x.deduction_month', [$f['date_from'], $f['date_to']])->when($f['department_id'], fn($q, $v) => $q->where('p.lib_department_id', $v))
            ->select('x.deduction_month', 'u.name', 'd.name as department', 'x.deduction_type', 'x.amount', 'x.reason', 'x.applied_payslip_id')->orderBy('x.deduction_month')->get()
            ->map(fn($r) => (array) $r + ['state' => $r->applied_payslip_id ? 'Taken' : 'Waiting'])->all();
        return [$rows, ['deduction_month' => ['Month', 'date'], 'name' => ['Employee', 'text'], 'department' => ['Department', 'text'], 'deduction_type' => ['Type', 'text'], 'amount' => ['Amount', 'money', 1], 'reason' => ['Reason', 'text'], 'state' => ['State', 'badge']]];
    }

    private static function r_pf_balance(array $f): array
    {
        $rows = DB::table('hr_pf_entries as e')->join('admin_users as u', 'u.id', '=', 'e.admin_user_id')->leftJoin('hr_profiles as p', 'p.admin_user_id', '=', 'u.id')->leftJoin('lib_departments as d', 'd.id', '=', 'p.lib_department_id')
            ->when($f['department_id'], fn($q, $v) => $q->where('p.lib_department_id', $v))
            ->selectRaw("u.name, d.name as department,
                SUM(CASE WHEN e.entry_type = 'Contribution' THEN e.employee_amount ELSE 0 END) as employee_share,
                SUM(CASE WHEN e.entry_type = 'Contribution' THEN e.employer_amount ELSE 0 END) as employer_share,
                SUM(CASE WHEN e.entry_type = 'Opening' THEN e.amount ELSE 0 END) as opening,
                SUM(CASE WHEN e.entry_type = 'Interest' THEN e.amount ELSE 0 END) as interest,
                SUM(CASE WHEN e.entry_type = 'Withdrawal' THEN -e.amount ELSE 0 END) as withdrawn,
                SUM(e.amount) as balance")->groupBy('u.id', 'u.name', 'd.name')->orderBy('u.name')->get()->all();
        return [$rows, ['name' => ['Employee', 'text'], 'department' => ['Department', 'text'], 'opening' => ['Opening', 'money', 1], 'employee_share' => ['Employee share', 'money', 1], 'employer_share' => ['Employer share', 'money', 1],
            'interest' => ['Interest', 'money', 1], 'withdrawn' => ['Withdrawn', 'money', 1], 'balance' => ['Balance', 'money', 1]]];
    }
}
