<?php

/*
 * API mirror of routes/admin/hrm/hr-pay-component-crud.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\Staff\PayComponent\Crud\HrPayComponentCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/pay-component',HrPayComponentCrudController::class)->except(['destroy', 'show']);
    Route::post('hrm/staff/pay-component/list',[HrPayComponentCrudController::class,'list']);
    Route::post('hrm/staff/pay-component/delete-list',[HrPayComponentCrudController::class,'deleteList']);
    Route::post('hrm/staff/pay-component/update-list',[HrPayComponentCrudController::class,'updateList']);
    //vpx_attach
});
