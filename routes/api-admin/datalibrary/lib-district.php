<?php

/*
 * API mirror of routes/admin/datalibrary/lib-district.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\DataLibrary\District\Crud\LibDistrictController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/location/district',LibDistrictController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/location/district/list',[LibDistrictController::class,'list']);
    Route::post('datalibrary/location/district/delete-list',[LibDistrictController::class,'deleteList']);
    Route::post('datalibrary/location/district/update-list',[LibDistrictController::class,'updateList']);
    //vpx_attach
});
