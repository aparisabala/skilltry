<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class HrDeduction extends Model
{
    use BaseTrait;
    protected $table = "hr_deductions";
    protected $fillable = [
        'admin_user_id',
        'deduction_type',
        'amount',
        'deduction_month',
        'reason',
        'applied_payslip_id',
        'serial',
    ];

    public function employee()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }
    //vpx_attach
}
