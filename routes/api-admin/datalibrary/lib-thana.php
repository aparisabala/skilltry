<?php

/*
 * API mirror of routes/admin/datalibrary/lib-thana.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\DataLibrary\Thana\Crud\LibThanaController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/location/thana',LibThanaController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/location/thana/list',[LibThanaController::class,'list']);
    Route::post('datalibrary/location/thana/delete-list',[LibThanaController::class,'deleteList']);
    Route::post('datalibrary/location/thana/update-list',[LibThanaController::class,'updateList']);
    //vpx_attach
});
