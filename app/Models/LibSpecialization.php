<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class LibSpecialization extends Model
{
    use BaseTrait;
    protected $table = "lib_specializations";
    protected $fillable = ['name', 'lib_category_id', 'lib_subcategory_id', 'image', 'extension', 'serial'];

    public function category()
    {
        return $this->belongsTo(LibCategory::class, 'lib_category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(LibSubcategory::class, 'lib_subcategory_id');
    }

    //vpx_attach
}
