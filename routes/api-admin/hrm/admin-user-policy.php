<?php

/*
 * API mirror of routes/admin/hrm/admin-user-policy.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\User\Policy\AdminUserPolicyController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function(){
    Route::get('hrm/user/user-policy',[AdminUserPolicyController::class,'index']);
    Route::post('hrm/user/user-policy',[AdminUserPolicyController::class,'update']);
});
