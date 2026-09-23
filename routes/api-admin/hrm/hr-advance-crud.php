<?php

/*
 * API mirror of routes/admin/hrm/hr-advance-crud.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\Staff\Advance\Crud\HrAdvanceCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/advance',HrAdvanceCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/staff/advance/{admin_user_id}',[HrAdvanceCrudController::class,'index'])->whereNumber('admin_user_id');
    Route::post('hrm/staff/advance/list',[HrAdvanceCrudController::class,'list']);
    Route::post('hrm/staff/advance/delete-list',[HrAdvanceCrudController::class,'deleteList']);
    Route::post('hrm/staff/advance/update-list',[HrAdvanceCrudController::class,'updateList']);
    //vpx_attach
});
