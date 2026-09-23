<?php

/*
 * API mirror of routes/admin/hrm/hr-education-crud.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\Staff\Education\Crud\HrEducationCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/education',HrEducationCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/staff/education/{admin_user_id}',[HrEducationCrudController::class,'index'])->whereNumber('admin_user_id');
    Route::post('hrm/staff/education/list',[HrEducationCrudController::class,'list']);
    Route::post('hrm/staff/education/delete-list',[HrEducationCrudController::class,'deleteList']);
    Route::post('hrm/staff/education/update-list',[HrEducationCrudController::class,'updateList']);
    //vpx_attach
});
