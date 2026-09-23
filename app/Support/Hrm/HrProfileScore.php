<?php

namespace App\Support\Hrm;

use App\Models\AdminUser;
use App\Models\HrEducation;
use App\Models\HrEmployment;
use App\Models\HrSalarySetup;

/**
 * How complete the HR file of an employee is (the "profile scale"): weighted checks over personal info,
 * education, employment history and salary setup.
 */
class HrProfileScore
{
    private const PERSONAL = [
        'gender' => ['Gender', 5], 'date_of_birth' => ['Date of birth', 8], 'nid_no' => ['NID / ID number', 8], 'present_address' => ['Present address', 6],
        'permanent_address' => ['Permanent address', 4], 'emergency_phone' => ['Emergency contact', 8], 'join_date' => ['Joining date', 10],
        'lib_department_id' => ['Department', 6], 'designation_title' => ['Job title', 6], 'bank_account' => ['Bank account', 6],
    ];

    /**
     * @return array{score: int, missing: array<int, string>, done: array<int, string>}
     */
    public static function for(AdminUser $u): array
    {
        $p = $u->hrProfile;
        $done = $missing = [];
        $total = $got = 0;
        foreach (self::PERSONAL as $field => [$label, $weight]) {
            $total += $weight;
            if ($p && !empty($p->{$field})) {
                $got += $weight;
                $done[] = $label;
            } else {
                $missing[] = $label;
            }
        }
        $checks = [
            ['Education record', 15, HrEducation::where('admin_user_id', $u->id)->exists()],
            ['Employment history', 8, HrEmployment::where('admin_user_id', $u->id)->exists()],
            ['Salary setup', 14, HrSalarySetup::where('admin_user_id', $u->id)->where('basic', '>', 0)->exists()],
        ];
        foreach ($checks as [$label, $weight, $ok]) {
            $total += $weight;
            if ($ok) {
                $got += $weight;
                $done[] = $label;
            } else {
                $missing[] = $label;
            }
        }
        return ['score' => (int) round($got / $total * 100), 'missing' => $missing, 'done' => $done];
    }
}
