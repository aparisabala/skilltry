<?php

/*
 * API mirror of routes/admin/hrm/hr-experience-crud.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\Staff\Experience\Crud\HrExperienceCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/experience',HrExperienceCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/staff/experience/{admin_user_id}',[HrExperienceCrudController::class,'index'])->whereNumber('admin_user_id');
    Route::post('hrm/staff/experience/list',[HrExperienceCrudController::class,'list']);
    Route::post('hrm/staff/experience/delete-list',[HrExperienceCrudController::class,'deleteList']);
    Route::post('hrm/staff/experience/update-list',[HrExperienceCrudController::class,'updateList']);
    //vpx_attach
});
