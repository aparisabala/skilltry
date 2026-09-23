<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class HrEmployment extends Model
{
    use BaseTrait;
    protected $table = "hr_employments";
    protected $fillable = [
        'admin_user_id',
        'change_type',
        'effective_date',
        'lib_department_id',
        'designation_title',
        'salary',
        'note',
        'serial',
    ];

    public function employee()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }

    public function department()
    {
        return $this->belongsTo(LibDepartment::class, 'lib_department_id');
    }
    //vpx_attach
}
