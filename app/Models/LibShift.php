<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class LibShift extends Model
{
    use BaseTrait;
    protected $table = "lib_shifts";
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'serial'
    ];
    //vpx_attach
}
