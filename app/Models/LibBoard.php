<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class LibBoard extends Model
{
    use BaseTrait;
    protected $table = "lib_boards";
    protected $fillable = ['name'];

    //vpx_attach
}
