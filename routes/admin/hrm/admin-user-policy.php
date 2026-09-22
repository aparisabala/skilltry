<?php

use App\Http\Controllers\Admin\Hrm\User\Policy\AdminUserPolicyController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function(){
    Route::get('hrm/user/user-policy',[AdminUserPolicyController::class,'index']);
    Route::post('hrm/user/user-policy',[AdminUserPolicyController::class,'update']);
});
