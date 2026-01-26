<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Logout\AdminUserLogoutController;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::get('logout', [AdminUserLogoutController::class,'logout'])->name('admin.logout');
    //vpx_attach
});
//vpx_attach
