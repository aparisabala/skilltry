<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;

class AdminUserPermission extends Model
{
    use BaseTrait;
    protected $table = 'admin_user_permissions';
    protected $fillable = ['slug'];
    protected $casts = [
        'user_access' => 'array'
    ];
}
