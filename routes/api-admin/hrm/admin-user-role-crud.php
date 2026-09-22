<?php

/*
 * API mirror of routes/admin/hrm/admin-user-role-crud.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\User\UserRole\Crud\AdminUserRoleCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/user/user-role',AdminUserRoleCrudController::class)->except(['destroy', 'show']);
    Route::post('hrm/user/user-role/list',[AdminUserRoleCrudController::class,'list']);
    Route::post('hrm/user/user-role/delete-list',[AdminUserRoleCrudController::class,'deleteList']);
    Route::post('hrm/user/user-role/update-list',[AdminUserRoleCrudController::class,'updateList']);
    //vpx_attach
});
