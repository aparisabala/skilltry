<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrPfEntry extends Model
{
    protected $table = 'hr_pf_entries';

    protected $fillable = [
        'admin_user_id',
        'entry_date',
        'entry_type',
        'employee_amount',
        'employer_amount',
        'amount',
        'hr_payslip_id',
        'note',
        'created_by',
    ];

    protected $casts = ['entry_date' => 'date:Y-m-d'];
}
