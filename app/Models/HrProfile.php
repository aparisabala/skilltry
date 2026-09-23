<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrProfile extends Model
{
    protected $table = 'hr_profiles';

    protected $fillable = [
        'admin_user_id',
        'employee_code',
        'gender',
        'date_of_birth',
        'blood_group',
        'marital_status',
        'religion',
        'nationality',
        'nid_no',
        'tin_no',
        'father_name',
        'mother_name',
        'spouse_name',
        'present_address',
        'permanent_address',
        'emergency_name',
        'emergency_phone',
        'emergency_relation',
        'join_date',
        'confirmation_date',
        'lib_department_id',
        'designation_title',
        'employment_type',
        'employee_status',
        'resign_date',
        'bank_name',
        'bank_branch',
        'bank_account',
        'note',
    ];

    public function department()
    {
        return $this->belongsTo(LibDepartment::class, 'lib_department_id');
    }
}
