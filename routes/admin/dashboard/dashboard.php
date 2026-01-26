<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Dashboard\AdminUserDashboardController;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::get('dashboard', [AdminUserDashboardController::class,'index'])->name('admin.dashboard.index');
    //vpx_attach
});
//vpx_attach
