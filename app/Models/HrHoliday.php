<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class HrHoliday extends Model
{
    use BaseTrait;
    protected $table = "hr_holidays";
    protected $fillable = [
        'name',
        'holiday_date',
        'note',
        'serial',
    ];
    //vpx_attach
}
