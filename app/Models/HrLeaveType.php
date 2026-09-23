<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class HrLeaveType extends Model
{
    use BaseTrait;
    protected $table = "hr_leave_types";
    protected $fillable = [
        'name',
        'is_paid',
        'days_per_year',
        'status',
        'serial',
    ];

    //vpx_attach
}
