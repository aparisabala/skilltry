<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class LibSkill extends Model
{
    use BaseTrait;
    protected $table = "lib_skills";
    protected $fillable = ['name'];

    //vpx_attach
}
