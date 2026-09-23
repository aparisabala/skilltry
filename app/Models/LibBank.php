<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class LibBank extends Model
{
    use BaseTrait;
    protected $table = "lib_banks";
    protected $fillable = ['name'];

    //vpx_attach
}
