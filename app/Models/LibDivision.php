<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class LibDivision extends Model
{
    use BaseTrait;
    protected $table = "lib_divisions";
    protected $fillable = ['name'];

    //vpx_attach
}
