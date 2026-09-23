<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class LibCategory extends Model
{
    use BaseTrait;
    protected $table = "lib_categories";
    protected $fillable = ['name', 'image', 'extension', 'serial'];

    //vpx_attach
}
