<?php

/*
 * API mirror of routes/admin/datalibrary/lib-degree.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\DataLibrary\Degree\Crud\LibDegreeController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/degree',LibDegreeController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/degree/list',[LibDegreeController::class,'list']);
    Route::post('datalibrary/degree/delete-list',[LibDegreeController::class,'deleteList']);
    Route::post('datalibrary/degree/update-list',[LibDegreeController::class,'updateList']);
    //vpx_attach
});
