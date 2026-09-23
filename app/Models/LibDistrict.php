<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class LibDistrict extends Model
{
    use BaseTrait;
    protected $table = "lib_districts";
    protected $fillable = ['name', 'lib_division_id'];

    public function division()
    {
        return $this->belongsTo(LibDivision::class, 'lib_division_id');
    }
    //vpx_attach
}
