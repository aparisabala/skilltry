<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrPayslip extends Model
{
    protected $table = 'hr_payslips';

    protected $fillable = [
        'hr_payroll_id',
        'admin_user_id',
        'basic',
        'total_allowance',
        'gross',
        'days_in_month',
        'working_days',
        'present_days',
        'paid_leave_days',
        'absent_days',
        'late_count',
        'absent_deduction',
        'late_deduction',
        'pf_employee',
        'pf_employer',
        'component_deduction',
        'advance_deduction',
        'other_deduction',
        'total_deduction',
        'net',
        'paid_amount',
        'status',
        'breakdown',
    ];

    protected $casts = ['breakdown' => 'array'];

    public function employee()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }

    public function payroll()
    {
        return $this->belongsTo(HrPayroll::class, 'hr_payroll_id');
    }

    public function payments()
    {
        return $this->hasMany(HrSalaryPayment::class, 'hr_payslip_id');
    }
}
