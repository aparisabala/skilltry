<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrSetting extends Model
{
    protected $table = 'hr_settings';

    protected $fillable = [
        'setting_key',
        'setting_value',
    ];
}
