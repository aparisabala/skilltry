<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
class AdminUserOpdFees extends Model
{
    use BaseTrait;
    protected $table = "admin_user_opd_fees";
    protected $fillable = [
        'admin_user_id',
        'doctor_fees',
        'hospital_fees',
        'service_fees',
        'ipd_fees'
    ];

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id', 'id');
    }
    //vpx_attach
}
