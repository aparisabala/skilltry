<?php

use App\Http\Controllers\Admin\System\User\UserRole\Crud\AdminUserRoleCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('system/user/user-role',AdminUserRoleCrudController::class)->except(['destroy', 'show']);
    Route::post('system/user/user-role/list',[AdminUserRoleCrudController::class,'list']);
    Route::post('system/user/user-role/delete-list',[AdminUserRoleCrudController::class,'deleteList']);
    Route::post('system/user/user-role/update-list',[AdminUserRoleCrudController::class,'updateList']);
    //vpx_attach
});
