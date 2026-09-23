<?php

/*
 * API mirror of routes/admin/hrm/admin-user-opd-slot-crud.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\User\Crud\Modify\DoctorOpdSlot\Crud\AdminUserOpdSlotCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/user/crud/modify/doctor-opd-slot',AdminUserOpdSlotCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/user/crud/modify/doctor-opd-slot/{admin_user_id}',[AdminUserOpdSlotCrudController::class,'index']);
    Route::post('hrm/user/crud/modify/doctor-opd-slot/list',[AdminUserOpdSlotCrudController::class,'list']);
    Route::post('hrm/user/crud/modify/doctor-opd-slot/delete-list',[AdminUserOpdSlotCrudController::class,'deleteList']);
    Route::post('hrm/user/crud/modify/doctor-opd-slot/update-list',[AdminUserOpdSlotCrudController::class,'updateList']);
    //vpx_attach
});
