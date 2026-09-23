<?php

/*
 * API mirror of routes/admin/hrm/admin-user-opd-fees-opd-fees-update.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\User\Crud\Modify\OpdFees\Form\Update\AdminUserOpdFeesOpdFeesUpdateController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::get('hrm/user/crud/modify/opd-fees/update/{admin_user_id}',[AdminUserOpdFeesOpdFeesUpdateController::class,'index']);
    Route::post('hrm/user/crud/modify/opd-fees/update',[AdminUserOpdFeesOpdFeesUpdateController::class,'update']);
    //vpx_attach
});
