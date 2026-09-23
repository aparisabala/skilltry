<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class HrPayComponent extends Model
{
    use BaseTrait;
    protected $table = "hr_pay_components";
    protected $fillable = [
        'name',
        'component_type',
        'calc_type',
        'default_value',
        'status',
        'serial',
    ];
    //vpx_attach
}
