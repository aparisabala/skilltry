<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
//vpx_imports
//hasAuth
//crudDone
class AdminUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, BaseTrait;
    protected $table = 'admin_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile_numnber',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'user_access' => 'array'
    ];

    public function hasPermission($permissions)
    {
        return (in_array('SA',$this->user_access)) ? true : count(array_intersect($this->user_access, $permissions)) > 0;
    }

    //vpx_attach
}
