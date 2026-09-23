<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class HrEducation extends Model
{
    use BaseTrait;
    protected $table = "hr_educations";
    protected $fillable = [
        'admin_user_id',
        'degree',
        'institute',
        'board_university',
        'subject',
        'passing_year',
        'result',
        'description',
        'serial',
    ];

    public function employee()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }
    //vpx_attach
}
