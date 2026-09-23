<?php

namespace App\Support\Hrm;

use App\Models\AdminUser;
use App\Models\HrAttendance;
use App\Models\HrHoliday;
use App\Models\HrLeave;
use App\Models\HrRoster;
use App\Models\LibShift;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Roster (who works which shift on which day) and attendance (check in / out, late, absent) of the staff.
 */
class HrAttendanceService
{
    public static function actor(): ?int
    {
        return auth('admin')->id() ?? request()->user('admin_api')?->id;
    }

    // ---------------------------------------------------------------------------------------------- roster

    /**
     * Roster of a period: [user_id][Y-m-d] => HrRoster
     */
    public static function rosterMap(string $from, string $to, ?array $userIds = null): array
    {
        $map = [];
        foreach (HrRoster::whereBetween('roster_date', [$from, $to])->when($userIds, fn($q) => $q->whereIn('admin_user_id', $userIds))->get() as $r) {
            $map[$r->admin_user_id][$r->roster_date->toDateString()] = $r;
        }
        return $map;
    }

    /**
     * Set a day of the roster. shift null = day off. Nothing is stored for "clear" (shift = false).
     */
    public static function setRoster(int $userId, string $date, $shift, ?string $note = null): void
    {
        if ($shift === false) {
            HrRoster::where('admin_user_id', $userId)->where('roster_date', $date)->delete();
            return;
        }
        HrRoster::updateOrCreate(['admin_user_id' => $userId, 'roster_date' => $date], ['lib_shift_id' => $shift ?: null, 'note' => $note, 'created_by' => self::actor()]);
    }

    /**
     * Repeat the roster of the week before onto the week that starts at $weekStart
     */
    public static function copyWeek(string $weekStart, ?array $userIds = null): int
    {
        $start = Carbon::parse($weekStart)->startOfDay();
        $n = 0;
        foreach (HrRoster::whereBetween('roster_date', [$start->copy()->subDays(7)->toDateString(), $start->copy()->subDay()->toDateString()])
            ->when($userIds, fn($q) => $q->whereIn('admin_user_id', $userIds))->get() as $r) {
            self::setRoster($r->admin_user_id, $r->roster_date->copy()->addDays(7)->toDateString(), $r->lib_shift_id, $r->note);
            $n++;
        }
        return $n;
    }

    /**
     * The shift an employee works on a date: null when the day is off or nothing is planned
     */
    public static function shiftFor(int $userId, string $date): ?LibShift
    {
        $r = HrRoster::where('admin_user_id', $userId)->where('roster_date', $date)->first();
        return $r && $r->lib_shift_id ? LibShift::find($r->lib_shift_id) : null;
    }

    // ---------------------------------------------------------------------------------------------- times

    private static function at(string $date, ?string $time): ?Carbon
    {
        if (!$time) {
            return null;
        }
        try {
            $t = Carbon::parse($time);
            return Carbon::parse($date)->setTime($t->hour, $t->minute, 0);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Status, late / early / worked minutes of a day from the times and the shift
     *
     * @return array{status: string, late: int, early: int, worked: int}
     */
    public static function evaluate(string $date, ?LibShift $shift, ?Carbon $in, ?Carbon $out): array
    {
        $cfg = HrSettings::all();
        $late = $early = $worked = 0;
        $status = 'Present';
        if ($in && $out) {
            if ($out->lt($in)) {
                $out = $out->copy()->addDay();
            }
            $worked = $in->diffInMinutes($out);
        }
        if ($shift && $in) {
            $start = self::at($date, $shift->start_time);
            if ($start && $in->gt($start->copy()->addMinutes((int) $cfg['grace_minutes']))) {
                $late = $start->diffInMinutes($in);
                $status = 'Late';
            }
            if ($out) {
                $end = self::at($date, $shift->end_time);
                if ($end && $start && $end->lt($start)) {
                    $end->addDay();
                }
                if ($end && $out->lt($end)) {
                    $early = $out->diffInMinutes($end);
                }
            }
        }
        if ($out && $worked > 0 && $worked < (int) $cfg['half_day_minutes']) {
            $status = 'Half Day';
        }
        return ['status' => $status, 'late' => $late, 'early' => $early, 'worked' => $worked];
    }

    // ---------------------------------------------------------------------------------------------- attendance

    /**
     * Record a day. $d: status (Present, Late, Absent, Half Day; worked out from the times when left out),
     * check_in, check_out (H:i or a date time), note
     */
    public static function mark(int $userId, string $date, array $d, string $source = 'Manual'): HrAttendance
    {
        $shift = self::shiftFor($userId, $date);
        $in = self::at($date, $d['check_in'] ?? null);
        $out = self::at($date, $d['check_out'] ?? null);
        $status = $d['status'] ?? null;
        if ($status === 'Absent') {
            $calc = ['status' => 'Absent', 'late' => 0, 'early' => 0, 'worked' => 0];
            $in = $out = null;
        } else {
            $calc = self::evaluate($date, $shift, $in, $out);
            if ($status && $status !== 'Present') {
                $calc['status'] = $status;
            } elseif ($status === 'Present' && $calc['status'] === 'Late' && empty($d['keep_late'])) {
                // the sheet says present: the times still decide "late" when a shift is rostered
            }
        }
        return HrAttendance::updateOrCreate(['admin_user_id' => $userId, 'work_date' => $date], [
            'lib_shift_id' => $shift?->id, 'check_in' => $in, 'check_out' => $out, 'status' => $calc['status'],
            'late_minutes' => $calc['late'], 'early_minutes' => $calc['early'], 'worked_minutes' => $calc['worked'],
            'source' => $source, 'note' => $d['note'] ?? null, 'created_by' => self::actor(),
        ]);
    }

    /**
     * The signed in person checks in (now, or $at). One check in per day.
     *
     * @return array{0: ?HrAttendance, 1: ?string} record and error
     */
    public static function checkIn(int $userId, ?Carbon $at = null, string $source = 'Web'): array
    {
        $at = $at ?: now();
        $date = $at->toDateString();
        $row = HrAttendance::where('admin_user_id', $userId)->where('work_date', $date)->first();
        if ($row && $row->check_in) {
            return [$row, 'Already checked in today at '.$row->check_in->format('h:i A')];
        }
        return [self::mark($userId, $date, ['check_in' => $at->format('H:i')], $source), null];
    }

    /**
     * Check out: the last check in without a check out (also from the day before, for a night shift)
     *
     * @return array{0: ?HrAttendance, 1: ?string}
     */
    public static function checkOut(int $userId, ?Carbon $at = null, string $source = 'Web'): array
    {
        $at = $at ?: now();
        $row = HrAttendance::where('admin_user_id', $userId)->whereNotNull('check_in')->whereNull('check_out')
            ->whereBetween('work_date', [$at->copy()->subDay()->toDateString(), $at->toDateString()])->orderByDesc('work_date')->first();
        if (!$row) {
            return [null, 'No open check in to close. Check in first'];
        }
        $date = $row->work_date->toDateString();
        $in = $row->check_in;
        $out = $at->copy();
        $calc = self::evaluate($date, self::shiftFor($userId, $date), $in, $out);
        $row->update([
            'check_out' => $out, 'status' => $calc['status'], 'late_minutes' => $calc['late'], 'early_minutes' => $calc['early'],
            'worked_minutes' => $calc['worked'], 'source' => $source,
        ]);
        return [$row->fresh(), null];
    }

    // ---------------------------------------------------------------------------------------------- calendar of a day

    /** Why a date is not a working day for an employee (Holiday, Weekly off, Day off) or null */
    public static function offReason(string $date, ?HrRoster $roster, array $holidays, array $weeklyOff): ?string
    {
        if (isset($holidays[$date])) {
            return 'Holiday: '.$holidays[$date];
        }
        if ($roster) {
            return $roster->lib_shift_id ? null : 'Day off';
        }
        return in_array(Carbon::parse($date)->format('D'), $weeklyOff, true) ? 'Weekly off' : null;
    }

    public static function holidayMap(string $from, string $to): array
    {
        return HrHoliday::whereBetween('holiday_date', [$from, $to])->pluck('name', 'holiday_date')->mapWithKeys(fn($n, $d) => [Carbon::parse($d)->toDateString() => $n])->all();
    }

    /** Approved leave days of the period: [user_id][Y-m-d] => [type name, paid yes/no] */
    public static function leaveMap(string $from, string $to, ?array $userIds = null): array
    {
        $map = [];
        $leaves = HrLeave::with('leaveType:id,name,is_paid')->where('status', 'Approved')->where('from_date', '<=', $to)->where('to_date', '>=', $from)
            ->when($userIds, fn($q) => $q->whereIn('admin_user_id', $userIds))->get();
        foreach ($leaves as $l) {
            for ($d = Carbon::parse(max($l->from_date, $from)); $d->lte(Carbon::parse(min($l->to_date, $to))); $d->addDay()) {
                $map[$l->admin_user_id][$d->toDateString()] = [$l->leaveType?->name ?? 'Leave', $l->leaveType?->is_paid ?? 'Yes'];
            }
        }
        return $map;
    }

    /**
     * The daily attendance sheet: every active employee with the record of the date and what the calendar says
     * (leave, holiday, day off, rostered shift). Filters: department_id, term.
     */
    public static function sheet(string $date, array $f = []): Collection
    {
        $users = AdminUser::with(['role:id,name', 'hrProfile'])->where('status', 'Active')
            ->when($f['department_id'] ?? null, fn($q, $v) => $q->whereHas('hrProfile', fn($p) => $p->where('lib_department_id', $v)))
            ->when($f['term'] ?? null, fn($q, $v) => $q->where('name', 'like', '%'.$v.'%'))
            ->orderBy('name')->get();
        $ids = $users->pluck('id')->all();
        $att = HrAttendance::where('work_date', $date)->whereIn('admin_user_id', $ids)->get()->keyBy('admin_user_id');
        $roster = self::rosterMap($date, $date, $ids);
        $leaves = self::leaveMap($date, $date, $ids);
        $holidays = self::holidayMap($date, $date);
        $off = HrSettings::get('weekly_off');
        $shifts = LibShift::pluck('name', 'id');
        return $users->map(function ($u) use ($att, $roster, $leaves, $holidays, $off, $date, $shifts) {
            $r = $roster[$u->id][$date] ?? null;
            return [
                'user' => $u, 'attendance' => $att[$u->id] ?? null,
                'shift' => $r && $r->lib_shift_id ? ($shifts[$r->lib_shift_id] ?? null) : null,
                'off' => self::offReason($date, $r, $holidays, $off),
                'leave' => $leaves[$u->id][$date] ?? null,
            ];
        });
    }

    /**
     * Month of one employee: counts and the days
     */
    public static function month(int $userId, string $ym): array
    {
        $from = Carbon::parse($ym.'-01')->startOfMonth();
        $to = $from->copy()->endOfMonth();
        $rows = HrAttendance::where('admin_user_id', $userId)->whereBetween('work_date', [$from->toDateString(), $to->toDateString()])->get()->keyBy(fn($r) => $r->work_date->toDateString());
        $roster = self::rosterMap($from->toDateString(), $to->toDateString(), [$userId])[$userId] ?? [];
        $leaves = self::leaveMap($from->toDateString(), $to->toDateString(), [$userId])[$userId] ?? [];
        $holidays = self::holidayMap($from->toDateString(), $to->toDateString());
        $off = HrSettings::get('weekly_off');
        $days = [];
        $sum = ['present' => 0, 'late' => 0, 'absent' => 0, 'half_day' => 0, 'leave' => 0, 'off' => 0, 'worked_minutes' => 0, 'late_minutes' => 0];
        for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
            $k = $d->toDateString();
            $r = $rows[$k] ?? null;
            $reason = self::offReason($k, $roster[$k] ?? null, $holidays, $off);
            $status = $r?->status ?? (isset($leaves[$k]) ? 'Leave' : ($reason ? 'Off' : null));
            match ($status) {
                'Present' => $sum['present']++, 'Late' => [$sum['present']++, $sum['late']++], 'Absent' => $sum['absent']++, 'Half Day' => $sum['half_day']++,
                'Leave' => $sum['leave']++, 'Off' => $sum['off']++, default => null,
            };
            $sum['worked_minutes'] += (int) ($r?->worked_minutes ?? 0);
            $sum['late_minutes'] += (int) ($r?->late_minutes ?? 0);
            $days[] = ['date' => $k, 'day' => $d->format('D'), 'status' => $status, 'note' => $r?->note ?? ($reason ?: ($leaves[$k][0] ?? null)),
                'check_in' => $r?->check_in?->format('h:i A'), 'check_out' => $r?->check_out?->format('h:i A'), 'late_minutes' => (int) ($r?->late_minutes ?? 0), 'worked_minutes' => (int) ($r?->worked_minutes ?? 0)];
        }
        return ['month' => $from->format('Y-m'), 'summary' => $sum, 'days' => $days];
    }
}
