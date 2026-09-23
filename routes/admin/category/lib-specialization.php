<?php

use App\Http\Controllers\Admin\Category\Specialization\Crud\LibSpecializationController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::get('category/{category}/subcategory/{subcategory}/specialization', [LibSpecializationController::class,'index']);
    Route::get('category/{category}/subcategory/{subcategory}/specialization/create', [LibSpecializationController::class,'create']);
    Route::post('category/{category}/subcategory/{subcategory}/specialization', [LibSpecializationController::class,'store']);
    Route::get('category/{category}/subcategory/{subcategory}/specialization/{specialization}/edit', [LibSpecializationController::class,'edit']);
    // literal-suffix POST routes must be registered before the parameterized
    // POST update route below, otherwise {specialization} would swallow "list" etc.
    Route::post('category/{category}/subcategory/{subcategory}/specialization/list', [LibSpecializationController::class,'list']);
    Route::post('category/{category}/subcategory/{subcategory}/specialization/delete-list', [LibSpecializationController::class,'deleteList']);
    Route::post('category/{category}/subcategory/{subcategory}/specialization/update-list', [LibSpecializationController::class,'updateList']);
    Route::post('category/{category}/subcategory/{subcategory}/specialization/{specialization}', [LibSpecializationController::class,'update']);
    //vpx_attach
});
