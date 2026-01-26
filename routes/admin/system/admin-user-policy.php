<?php

use App\Http\Controllers\Admin\System\User\Policy\AdminUserPolicyController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function(){
    Route::get('system/user/user-policy',[AdminUserPolicyController::class,'index']);
    Route::post('system/user/user-policy',[AdminUserPolicyController::class,'update']);
});
