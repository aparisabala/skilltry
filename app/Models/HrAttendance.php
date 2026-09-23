<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrAttendance extends Model
{
    protected $table = 'hr_attendances';

    protected $fillable = [
        'admin_user_id',
        'work_date',
        'lib_shift_id',
        'check_in',
        'check_out',
        'status',
        'late_minutes',
        'early_minutes',
        'worked_minutes',
        'source',
        'note',
        'created_by',
    ];

    protected $casts = ['work_date' => 'date:Y-m-d', 'check_in' => 'datetime', 'check_out' => 'datetime'];

    public function employee()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }

    public function shift()
    {
        return $this->belongsTo(LibShift::class, 'lib_shift_id');
    }
}
