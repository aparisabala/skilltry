<?php

/*
 * API mirror of routes/admin/hrm/hr-pay-grade-crud.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\Staff\PayGrade\Crud\HrPayGradeCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/pay-grade',HrPayGradeCrudController::class)->except(['destroy', 'show']);
    Route::post('hrm/staff/pay-grade/list',[HrPayGradeCrudController::class,'list']);
    Route::post('hrm/staff/pay-grade/delete-list',[HrPayGradeCrudController::class,'deleteList']);
    Route::post('hrm/staff/pay-grade/update-list',[HrPayGradeCrudController::class,'updateList']);
    //vpx_attach
});
