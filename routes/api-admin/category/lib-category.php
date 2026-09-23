<?php

/*
 * API mirror of routes/admin/category/lib-category.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Category\Category\Crud\LibCategoryController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('category',LibCategoryController::class)->except(['destroy', 'show']);
    Route::post('category/list',[LibCategoryController::class,'list']);
    Route::post('category/delete-list',[LibCategoryController::class,'deleteList']);
    Route::post('category/update-list',[LibCategoryController::class,'updateList']);
    //vpx_attach
});
