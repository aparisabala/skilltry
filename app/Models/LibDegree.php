<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class LibDegree extends Model
{
    use BaseTrait;
    protected $table = "lib_degrees";
    protected $fillable = ['name'];

    //vpx_attach
}
