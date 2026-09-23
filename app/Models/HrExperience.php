<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class HrExperience extends Model
{
    use BaseTrait;
    protected $table = "hr_experiences";
    protected $fillable = [
        'admin_user_id',
        'organization',
        'position',
        'from_date',
        'to_date',
        'last_salary',
        'leaving_reason',
        'responsibilities',
        'serial',
    ];

    public function employee()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }
    //vpx_attach
}
