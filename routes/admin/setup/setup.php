<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Setup\AdminUserSetupController;
use App\Http\Controllers\Admin\Setup\AdminUserProfileUpdateController;

//vpx_imports
Route::prefix('admin')->group(function(){
    
    Route::get('setup/profile', [AdminUserSetupController::class,'index'])->name('admin.profile.setup');
    Route::post('setup/profile', [AdminUserSetupController::class,'update']);

    Route::get('setup/profile-update', [AdminUserProfileUpdateController::class,'index'])->name('admin.user.setup');
    Route::post('setup/profile-update', [AdminUserProfileUpdateController::class,'updateProfile']);
    Route::get('setup/password-update', [AdminUserProfileUpdateController::class,'password'])->name('admin.user.password');
    Route::post('setup/password-update', [AdminUserProfileUpdateController::class,'updatePassword']);
    //vpx_attach
});




