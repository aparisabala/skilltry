<?php

/*
 * API mirror of routes/admin/hrm/admin-user-designation-crud.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\User\Crud\Modify\Designation\Crud\AdminUserDesignationCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/user/crud/modify/designation',AdminUserDesignationCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/user/crud/modify/designation/{admin_user_id}',[AdminUserDesignationCrudController::class,'index']);
    Route::post('hrm/user/crud/modify/designation/list',[AdminUserDesignationCrudController::class,'list']);
    Route::post('hrm/user/crud/modify/designation/delete-list',[AdminUserDesignationCrudController::class,'deleteList']);
    Route::post('hrm/user/crud/modify/designation/update-list',[AdminUserDesignationCrudController::class,'updateList']);
    //vpx_attach
});
