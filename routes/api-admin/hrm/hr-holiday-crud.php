<?php

/*
 * API mirror of routes/admin/hrm/hr-holiday-crud.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\Staff\Holiday\Crud\HrHolidayCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/holiday',HrHolidayCrudController::class)->except(['destroy', 'show']);
    Route::post('hrm/staff/holiday/list',[HrHolidayCrudController::class,'list']);
    Route::post('hrm/staff/holiday/delete-list',[HrHolidayCrudController::class,'deleteList']);
    Route::post('hrm/staff/holiday/update-list',[HrHolidayCrudController::class,'updateList']);
    //vpx_attach
});
