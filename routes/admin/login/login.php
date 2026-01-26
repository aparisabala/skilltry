<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Login\AdminUserLoginController;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::get('login', [AdminUserLoginController::class,'index'])->name('admin.login.index');
    Route::post('login', [AdminUserLoginController::class,'login'])->name('admin.login');
    //vpx_attach
});
//vpx_attach
