<?php

use App\Http\Controllers\Admin\Hrm\Staff\Leave\Crud\HrLeaveCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/leave',HrLeaveCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/staff/leave/{admin_user_id}',[HrLeaveCrudController::class,'index'])->whereNumber('admin_user_id');
    Route::post('hrm/staff/leave/list',[HrLeaveCrudController::class,'list']);
    Route::post('hrm/staff/leave/delete-list',[HrLeaveCrudController::class,'deleteList']);
    Route::post('hrm/staff/leave/update-list',[HrLeaveCrudController::class,'updateList']);
    //vpx_attach
});
