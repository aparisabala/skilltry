<?php

use App\Http\Controllers\Admin\Hrm\User\UserRole\Crud\AdminUserRoleCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/user/user-role',AdminUserRoleCrudController::class)->except(['destroy', 'show']);
    Route::post('hrm/user/user-role/list',[AdminUserRoleCrudController::class,'list']);
    Route::post('hrm/user/user-role/delete-list',[AdminUserRoleCrudController::class,'deleteList']);
    Route::post('hrm/user/user-role/update-list',[AdminUserRoleCrudController::class,'updateList']);
    //vpx_attach
});
