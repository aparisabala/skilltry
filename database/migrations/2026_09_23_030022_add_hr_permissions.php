<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $slugs = [
        'hr_education_crud_view', 'hr_education_crud_store', 'hr_education_crud_edit', 'hr_education_crud_delete', 'hr_education_crud_bulk_update', 'hr_education_crud_pdf', 'hr_education_crud_excel',
        'hr_experience_crud_view', 'hr_experience_crud_store', 'hr_experience_crud_edit', 'hr_experience_crud_delete', 'hr_experience_crud_bulk_update', 'hr_experience_crud_pdf', 'hr_experience_crud_excel',
        'hr_employment_crud_view', 'hr_employment_crud_store', 'hr_employment_crud_edit', 'hr_employment_crud_delete', 'hr_employment_crud_bulk_update', 'hr_employment_crud_pdf', 'hr_employment_crud_excel',
        'hr_advance_crud_view', 'hr_advance_crud_store', 'hr_advance_crud_edit', 'hr_advance_crud_delete', 'hr_advance_crud_bulk_update', 'hr_advance_crud_pdf', 'hr_advance_crud_excel',
        'hr_deduction_crud_view', 'hr_deduction_crud_store', 'hr_deduction_crud_edit', 'hr_deduction_crud_delete', 'hr_deduction_crud_bulk_update', 'hr_deduction_crud_pdf', 'hr_deduction_crud_excel',
        'hr_leave_crud_view', 'hr_leave_crud_store', 'hr_leave_crud_edit', 'hr_leave_crud_delete', 'hr_leave_crud_bulk_update', 'hr_leave_crud_pdf', 'hr_leave_crud_excel',
        'hr_paycomponent_crud_view', 'hr_paycomponent_crud_store', 'hr_paycomponent_crud_edit', 'hr_paycomponent_crud_delete', 'hr_paycomponent_crud_bulk_update', 'hr_paycomponent_crud_pdf', 'hr_paycomponent_crud_excel',
        'hr_paygrade_crud_view', 'hr_paygrade_crud_store', 'hr_paygrade_crud_edit', 'hr_paygrade_crud_delete', 'hr_paygrade_crud_bulk_update', 'hr_paygrade_crud_pdf', 'hr_paygrade_crud_excel',
        'hr_leavetype_crud_view', 'hr_leavetype_crud_store', 'hr_leavetype_crud_edit', 'hr_leavetype_crud_delete', 'hr_leavetype_crud_bulk_update', 'hr_leavetype_crud_pdf', 'hr_leavetype_crud_excel',
        'hr_holiday_crud_view', 'hr_holiday_crud_store', 'hr_holiday_crud_edit', 'hr_holiday_crud_delete', 'hr_holiday_crud_bulk_update', 'hr_holiday_crud_pdf', 'hr_holiday_crud_excel',
        'lib_shift_crud_view', 'lib_shift_crud_store', 'lib_shift_crud_edit', 'lib_shift_crud_delete', 'lib_shift_crud_bulk_update', 'lib_shift_crud_pdf', 'lib_shift_crud_excel',
        'hr_employee_view', 'hr_profile_view', 'hr_profile_edit', 'hr_salary_setup_view', 'hr_salary_setup_edit',
        'hr_attendance_view', 'hr_attendance_store', 'hr_roster_view', 'hr_roster_store',
        'hr_payroll_view', 'hr_payroll_generate', 'hr_payroll_approve', 'hr_payroll_pay',
        'hr_pf_view', 'hr_pf_store',
        'hr_report_view', 'hr_report_excel',
        'hr_settings_view', 'hr_settings_edit',
    ];

    public function up(): void
    {
        $now = now();
        foreach ($this->slugs as $slug) {
            if (!DB::table('admin_user_permissions')->where('slug', $slug)->exists()) {
                DB::table('admin_user_permissions')->insert(['slug' => $slug, 'created_at' => $now, 'updated_at' => $now]);
            }
        }
    }

    public function down(): void
    {
        DB::table('admin_user_permissions')->whereIn('slug', $this->slugs)->delete();
    }
};
