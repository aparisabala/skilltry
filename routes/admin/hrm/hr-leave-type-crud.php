<?php

use App\Http\Controllers\Admin\Hrm\Staff\LeaveType\Crud\HrLeaveTypeCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/leave-type',HrLeaveTypeCrudController::class)->except(['destroy', 'show']);
    Route::post('hrm/staff/leave-type/list',[HrLeaveTypeCrudController::class,'list']);
    Route::post('hrm/staff/leave-type/delete-list',[HrLeaveTypeCrudController::class,'deleteList']);
    Route::post('hrm/staff/leave-type/update-list',[HrLeaveTypeCrudController::class,'updateList']);
    //vpx_attach
});
