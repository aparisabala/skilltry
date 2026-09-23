<?php

/*
 * API mirror of routes/admin/hrm/hr-leave-type-crud.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\Staff\LeaveType\Crud\HrLeaveTypeCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/leave-type',HrLeaveTypeCrudController::class)->except(['destroy', 'show']);
    Route::post('hrm/staff/leave-type/list',[HrLeaveTypeCrudController::class,'list']);
    Route::post('hrm/staff/leave-type/delete-list',[HrLeaveTypeCrudController::class,'deleteList']);
    Route::post('hrm/staff/leave-type/update-list',[HrLeaveTypeCrudController::class,'updateList']);
    //vpx_attach
});
