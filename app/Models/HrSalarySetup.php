<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrSalarySetup extends Model
{
    protected $table = 'hr_salary_setups';

    protected $fillable = [
        'admin_user_id',
        'hr_pay_grade_id',
        'basic',
        'effective_from',
        'pf_employee_percent',
        'pf_employer_percent',
        'deduct_absent',
        'late_fee_applies',
        'payment_method',
        'note',
    ];

    public function items()
    {
        return $this->hasMany(HrSalarySetupItem::class, 'hr_salary_setup_id');
    }

    public function grade()
    {
        return $this->belongsTo(HrPayGrade::class, 'hr_pay_grade_id');
    }
}
