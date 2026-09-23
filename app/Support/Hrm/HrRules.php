<?php

namespace App\Support\Hrm;

/**
 * Validation rules of the HR forms, shared by the admin pages and the API.
 */
class HrRules
{
    public const METHODS = ['Cash', 'Bank', 'Mobile Banking', 'Cheque'];

    public static function profile(): array
    {
        return [
            'employee_code' => 'nullable|string|max:50', 'gender' => 'nullable|in:Male,Female,Others', 'date_of_birth' => 'nullable|date', 'blood_group' => 'nullable|string|max:10',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed', 'religion' => 'nullable|string|max:50', 'nationality' => 'nullable|string|max:50',
            'nid_no' => 'nullable|string|max:50', 'tin_no' => 'nullable|string|max:50', 'father_name' => 'nullable|string|max:253', 'mother_name' => 'nullable|string|max:253',
            'spouse_name' => 'nullable|string|max:253', 'present_address' => 'nullable|string|max:253', 'permanent_address' => 'nullable|string|max:253',
            'emergency_name' => 'nullable|string|max:253', 'emergency_phone' => 'nullable|string|max:30', 'emergency_relation' => 'nullable|string|max:50',
            'join_date' => 'nullable|date', 'confirmation_date' => 'nullable|date|after_or_equal:join_date', 'lib_department_id' => 'nullable|integer|exists:lib_departments,id',
            'designation_title' => 'nullable|string|max:253', 'employment_type' => 'required|in:Permanent,Contract,Part-time,Probation,Intern',
            'employee_status' => 'required|in:Active,On Leave,Resigned,Terminated,Retired', 'resign_date' => 'nullable|date|after_or_equal:join_date',
            'bank_name' => 'nullable|string|max:253', 'bank_branch' => 'nullable|string|max:253', 'bank_account' => 'nullable|string|max:60', 'note' => 'nullable|string',
        ];
    }

    public static function salary(): array
    {
        return [
            'basic' => 'required|numeric|min:0|max:999999999', 'hr_pay_grade_id' => 'nullable|integer|exists:hr_pay_grades,id', 'effective_from' => 'nullable|date',
            'pf_employee_percent' => 'nullable|numeric|min:0|max:100', 'pf_employer_percent' => 'nullable|numeric|min:0|max:100',
            'deduct_absent' => 'required|in:Yes,No', 'late_fee_applies' => 'required|in:Yes,No', 'payment_method' => 'required|in:'.implode(',', self::METHODS), 'note' => 'nullable|string|max:253',
            'items' => 'nullable|array', 'items.*.hr_pay_component_id' => 'required_with:items|integer|exists:hr_pay_components,id', 'items.*.value' => 'nullable|numeric|min:0',
        ];
    }

    public static function pf(): array
    {
        return ['entry_type' => 'required|in:Opening,Interest,Withdrawal', 'amount' => 'required|numeric|min:0.01|max:999999999', 'entry_date' => 'required|date', 'note' => 'nullable|string|max:253'];
    }

    public static function payment(): array
    {
        return ['amount' => 'required|numeric|min:0.01', 'method' => 'required|in:'.implode(',', self::METHODS), 'paid_on' => 'required|date', 'reference' => 'nullable|string|max:253', 'note' => 'nullable|string|max:253'];
    }

    public static function generate(): array
    {
        return ['month' => ['required', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/']];
    }

    public static function settings(): array
    {
        return [
            'weekly_off' => 'nullable|array', 'weekly_off.*' => 'in:Sat,Sun,Mon,Tue,Wed,Thu,Fri', 'grace_minutes' => 'required|integer|min:0|max:240', 'late_rule' => 'required|in:none,days,fixed',
            'late_count_for_day' => 'required|integer|min:1|max:31', 'late_fee_per_instance' => 'required|numeric|min:0|max:999999', 'month_days_basis' => 'required|in:calendar,30',
            'absent_if_no_record' => 'required|in:Yes,No', 'half_day_minutes' => 'required|integer|min:0|max:720',
        ];
    }
}
