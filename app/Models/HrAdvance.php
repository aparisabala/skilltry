<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class HrAdvance extends Model
{
    use BaseTrait;
    protected $table = "hr_advances";
    protected $fillable = [
        'admin_user_id',
        'amount',
        'advance_date',
        'monthly_deduction',
        'reason',
        'status',
        'balance',
        'approved_by',
        'approved_at',
        'serial',
    ];

    public function employee()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }
    //vpx_attach
}
