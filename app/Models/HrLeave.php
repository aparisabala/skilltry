<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class HrLeave extends Model
{
    use BaseTrait;
    protected $table = "hr_leaves";
    protected $fillable = [
        'admin_user_id',
        'hr_leave_type_id',
        'from_date',
        'to_date',
        'reason',
        'status',
        'days',
        'approved_by',
        'serial',
    ];

    public function employee()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }

    public function hrLeaveType()
    {
        return $this->belongsTo(HrLeaveType::class, 'hr_leave_type_id');
    }
    //vpx_attach
}
