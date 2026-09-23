<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class AdminUserOpdSlot extends Model
{
    use BaseTrait;
    protected $table = "admin_user_opd_slots";
    protected $fillable = [
        'admin_user_id',
        'year',
        'month',
        'day',
        'slot',
    ];

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id', 'id');
    }
    //vpx_attach
}
