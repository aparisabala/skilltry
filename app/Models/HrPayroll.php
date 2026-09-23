<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrPayroll extends Model
{
    protected $table = 'hr_payrolls';

    protected $fillable = [
        'pay_month',
        'title',
        'status',
        'employees',
        'total_gross',
        'total_deduction',
        'total_net',
        'total_paid',
        'generated_by',
        'approved_by',
        'approved_at',
        'note',
    ];

    protected $casts = ['pay_month' => 'date:Y-m-d', 'approved_at' => 'datetime'];

    public function payslips()
    {
        return $this->hasMany(HrPayslip::class, 'hr_payroll_id');
    }
}
