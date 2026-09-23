<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrRoster extends Model
{
    protected $table = 'hr_rosters';

    protected $fillable = [
        'admin_user_id',
        'roster_date',
        'lib_shift_id',
        'note',
        'created_by',
    ];

    protected $casts = ['roster_date' => 'date:Y-m-d'];

    public function shift()
    {
        return $this->belongsTo(LibShift::class, 'lib_shift_id');
    }
}
