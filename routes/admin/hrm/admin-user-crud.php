<?php

use App\Http\Controllers\Admin\Hrm\User\Crud\AdminUserCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/user',AdminUserCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/user/user-list/{admin_user_role_id}',[AdminUserCrudController::class,'index']);
    Route::post('hrm/user/list',[AdminUserCrudController::class,'list']);
    Route::post('hrm/user/delete-list',[AdminUserCrudController::class,'deleteList']);
    Route::post('hrm/user/update-list',[AdminUserCrudController::class,'updateList']);
    //vpx_attach
});
