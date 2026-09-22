<?php

/*
 * API mirror of routes/admin/hrm/update-policy-item-modal.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\Hrm\User\Policy\Modal\UpdatePolicyItem\UpdatePolicyItemModalController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::post('hrm/user/policy/update-policy-item/display',[UpdatePolicyItemModalController::class,'display']);
    //vpx_attach
});