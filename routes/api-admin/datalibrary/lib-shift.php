<?php

/*
 * API mirror of routes/admin/datalibrary/lib-shift.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\DataLibrary\Shift\Crud\LibShiftController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/shift',LibShiftController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/shift/list',[LibShiftController::class,'list']);
    Route::post('datalibrary/shift/delete-list',[LibShiftController::class,'deleteList']);
    Route::post('datalibrary/shift/update-list',[LibShiftController::class,'updateList']);
    //vpx_attach
});
