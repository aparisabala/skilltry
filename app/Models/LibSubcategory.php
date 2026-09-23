<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class LibSubcategory extends Model
{
    use BaseTrait;
    protected $table = "lib_subcategories";
    protected $fillable = ['name', 'lib_category_id', 'image', 'extension', 'serial'];

    public function category()
    {
        return $this->belongsTo(LibCategory::class, 'lib_category_id');
    }

    //vpx_attach
}
