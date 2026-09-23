<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class AdminUserDesignation extends Model
{
    use BaseTrait;
    protected $table = "admin_user_designations";
    protected $fillable = [
        'admin_user_id',
        'name',
        'passing_year',
        'description',
        'serial'
    ];

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id', 'id');
    }
    //vpx_attach
}
