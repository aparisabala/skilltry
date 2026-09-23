<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrAdvanceRecovery extends Model
{
    protected $table = 'hr_advance_recoveries';

    protected $fillable = [
        'hr_advance_id',
        'hr_payslip_id',
        'amount',
        'recovered_on',
    ];
}
