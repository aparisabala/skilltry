<?php

namespace App\Support\Hrm;

use App\Models\HrSetting;

/**
 * HR rules that the attendance and payroll use. Stored as key / value rows (hr_settings), with defaults.
 */
class HrSettings
{
    public const DEFAULTS = [
        /** days of the week that are off when the roster says nothing: Sat Sun Mon Tue Wed Thu Fri */
        'weekly_off' => ['Fri'],
        /** minutes after the shift start that are still on time */
        'grace_minutes' => 10,
        /** none | days (every N late days cost one day of salary) | fixed (a fee for every late day) */
        'late_rule' => 'days',
        'late_count_for_day' => 3,
        'late_fee_per_instance' => 0,
        /** calendar (days of the month) | 30 (a month is always 30 days) */
        'month_days_basis' => 'calendar',
        /** Yes = a working day without an attendance record counts as absent (only for employees whose salary setup deducts absence) */
        'absent_if_no_record' => 'No',
        /** worked less than this many minutes on a day with a check out = Half Day */
        'half_day_minutes' => 240,
    ];

    public static function all(): array
    {
        $stored = HrSetting::pluck('setting_value', 'setting_key')->map(fn($v) => json_decode($v, true))->all();
        return array_replace(self::DEFAULTS, $stored);
    }

    public static function get(string $key)
    {
        return self::all()[$key] ?? null;
    }

    /**
     * Save the given settings (unknown keys are ignored, values are cast to the type of the default)
     */
    public static function save(array $in): array
    {
        $out = [];
        foreach (self::DEFAULTS as $k => $default) {
            if (!array_key_exists($k, $in)) {
                continue;
            }
            $v = $in[$k];
            if ($k === 'weekly_off') {
                $v = array_values(array_intersect((array) $v, ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri']));
            } elseif (is_int($default)) {
                $v = max(0, (int) $v);
            } elseif (is_float($default)) {
                $v = max(0, (float) $v);
            } elseif ($k === 'late_rule') {
                $v = in_array($v, ['none', 'days', 'fixed'], true) ? $v : 'days';
            } elseif ($k === 'month_days_basis') {
                $v = in_array($v, ['calendar', '30'], true) ? $v : 'calendar';
            } elseif ($k === 'absent_if_no_record') {
                $v = $v === 'Yes' ? 'Yes' : 'No';
            }
            HrSetting::updateOrCreate(['setting_key' => $k], ['setting_value' => json_encode($v)]);
            $out[$k] = $v;
        }
        return $out;
    }
}
