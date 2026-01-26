<?php

use App\Http\Controllers\Admin\Reset\AdminUserResetController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::get('reset',[AdminUserResetController::class,'index'])->name('admin.reset');
    Route::post('reset/send-code',[AdminUserResetController::class,'sendCode']);
    Route::post('reset/verify-code',[AdminUserResetController::class,'verifyCode']);
    Route::post('reset/change-pass',[AdminUserResetController::class,'changePass']);
    //vpx_attach
});


