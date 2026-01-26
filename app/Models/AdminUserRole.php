<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class AdminUserRole extends Model
{
    use BaseTrait;
    protected $table = "admin_user_roles";
    protected $fillable = [
        'name',
        'code'
    ];
    //vpx_attach
}
