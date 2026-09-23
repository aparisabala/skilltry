<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrSalaryPayment extends Model
{
    protected $table = 'hr_salary_payments';

    protected $fillable = [
        'hr_payslip_id',
        'admin_user_id',
        'amount',
        'method',
        'paid_on',
        'reference',
        'note',
        'paid_by',
    ];

    protected $casts = ['paid_on' => 'date:Y-m-d'];

    public function payer()
    {
        return $this->belongsTo(AdminUser::class, 'paid_by');
    }
}
