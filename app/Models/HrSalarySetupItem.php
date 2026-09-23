<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrSalarySetupItem extends Model
{
    protected $table = 'hr_salary_setup_items';

    protected $fillable = [
        'hr_salary_setup_id',
        'hr_pay_component_id',
        'value',
    ];

    public function component()
    {
        return $this->belongsTo(HrPayComponent::class, 'hr_pay_component_id');
    }
}
