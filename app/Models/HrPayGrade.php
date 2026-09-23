<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class HrPayGrade extends Model
{
    use BaseTrait;
    protected $table = "hr_pay_grades";
    protected $fillable = [
        'name',
        'min_basic',
        'max_basic',
        'description',
        'status',
        'serial',
    ];

    //vpx_attach
}
