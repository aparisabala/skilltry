<?php

/*
 * API mirror of routes/admin/hrm/hr-employment-crud.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\Staff\Employment\Crud\HrEmploymentCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/employment',HrEmploymentCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/staff/employment/{admin_user_id}',[HrEmploymentCrudController::class,'index'])->whereNumber('admin_user_id');
    Route::post('hrm/staff/employment/list',[HrEmploymentCrudController::class,'list']);
    Route::post('hrm/staff/employment/delete-list',[HrEmploymentCrudController::class,'deleteList']);
    Route::post('hrm/staff/employment/update-list',[HrEmploymentCrudController::class,'updateList']);
    //vpx_attach
});
