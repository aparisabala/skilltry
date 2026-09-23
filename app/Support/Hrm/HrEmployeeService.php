<?php

namespace App\Support\Hrm;

use App\Models\AdminUser;
use App\Models\HrEmployment;
use App\Models\HrPayComponent;
use App\Models\HrPayGrade;
use App\Models\HrProfile;
use App\Models\HrSalarySetup;
use Illuminate\Support\Facades\DB;

/**
 * Saving the personal info and the salary setup of an employee (web pages and API).
 */
class HrEmployeeService
{
    public static function saveProfile(AdminUser $e, array $data): HrProfile
    {
        $data = collect($data)->map(fn($x) => $x === '' ? null : $x)->all();
        $profile = HrProfile::updateOrCreate(['admin_user_id' => $e->id], $data);
        // the first joining date starts the employment history
        if ($profile->join_date && !HrEmployment::where('admin_user_id', $e->id)->exists()) {
            HrEmployment::create(['admin_user_id' => $e->id, 'change_type' => 'Joined', 'effective_date' => $profile->join_date, 'lib_department_id' => $profile->lib_department_id,
                'designation_title' => $profile->designation_title, 'salary' => $e->salarySetup?->basic, 'serial' => 1]);
        }
        return $profile;
    }

    /**
     * @param array $items [{hr_pay_component_id, value}]
     * @return string|null why it cannot be saved
     */
    public static function saveSalary(AdminUser $e, array $d, array $items): ?string
    {
        $grade = !empty($d['hr_pay_grade_id']) ? HrPayGrade::find($d['hr_pay_grade_id']) : null;
        if ($grade && ($d['basic'] < $grade->min_basic || $d['basic'] > $grade->max_basic)) {
            return 'The basic salary is outside the scale of grade '.$grade->name.' ('.number_format($grade->min_basic, 2).' - '.number_format($grade->max_basic, 2).')';
        }
        $items = collect($items)->filter(fn($i) => is_array($i) && !empty($i['hr_pay_component_id']))->keyBy('hr_pay_component_id');
        $valid = HrPayComponent::whereIn('id', $items->keys())->pluck('id');
        $fields = collect($d)->only(['basic', 'hr_pay_grade_id', 'effective_from', 'pf_employee_percent', 'pf_employer_percent', 'deduct_absent', 'late_fee_applies', 'payment_method', 'note'])
            ->map(fn($x) => $x === '' ? null : $x)->all();
        $fields['pf_employee_percent'] = $fields['pf_employee_percent'] ?? 0;
        $fields['pf_employer_percent'] = $fields['pf_employer_percent'] ?? 0;
        DB::transaction(function () use ($e, $d, $items, $valid, $fields) {
            $old = HrSalarySetup::where('admin_user_id', $e->id)->first();
            $setup = HrSalarySetup::updateOrCreate(['admin_user_id' => $e->id], $fields);
            $setup->items()->delete();
            foreach ($valid as $cid) {
                $setup->items()->create(['hr_pay_component_id' => $cid, 'value' => max(0, (float) ($items[$cid]['value'] ?? 0))]);
            }
            // a change of the basic goes to the employment history
            if ($old && (float) $old->basic !== (float) $d['basic'] && $d['basic'] > 0) {
                HrEmployment::create(['admin_user_id' => $e->id, 'change_type' => $d['basic'] > $old->basic ? 'Increment' : 'Demotion', 'effective_date' => $d['effective_from'] ?? now()->toDateString(),
                    'designation_title' => $e->hrProfile?->designation_title, 'lib_department_id' => $e->hrProfile?->lib_department_id, 'salary' => $d['basic'], 'note' => 'Basic '.number_format($old->basic, 2).' to '.number_format($d['basic'], 2), 'serial' => 0]);
            }
        });
        return null;
    }
}
