<?php

use App\Http\Controllers\Admin\Category\Subcategory\Crud\LibSubcategoryController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::get('category/{category}/subcategory', [LibSubcategoryController::class,'index']);
    Route::get('category/{category}/subcategory/create', [LibSubcategoryController::class,'create']);
    Route::post('category/{category}/subcategory', [LibSubcategoryController::class,'store']);
    Route::get('category/{category}/subcategory/{subcategory}/edit', [LibSubcategoryController::class,'edit']);
    // literal-suffix POST routes must be registered before the parameterized
    // POST update route below, otherwise {subcategory} would swallow "list" etc.
    Route::post('category/{category}/subcategory/list', [LibSubcategoryController::class,'list']);
    Route::post('category/{category}/subcategory/delete-list', [LibSubcategoryController::class,'deleteList']);
    Route::post('category/{category}/subcategory/update-list', [LibSubcategoryController::class,'updateList']);
    Route::post('category/{category}/subcategory/{subcategory}', [LibSubcategoryController::class,'update']);
    //vpx_attach
});
