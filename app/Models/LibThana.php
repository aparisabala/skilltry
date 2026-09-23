<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class LibThana extends Model
{
    use BaseTrait;
    protected $table = "lib_thanas";
    protected $fillable = ['name', 'lib_division_id', 'lib_district_id'];

    public function division()
    {
        return $this->belongsTo(LibDivision::class, 'lib_division_id');
    }

    public function district()
    {
        return $this->belongsTo(LibDistrict::class, 'lib_district_id');
    }
    //vpx_attach
}
