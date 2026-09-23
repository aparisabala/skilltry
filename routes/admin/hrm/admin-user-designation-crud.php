<?php

use App\Http\Controllers\Admin\Hrm\User\Crud\Modify\Designation\Crud\AdminUserDesignationCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/user/crud/modify/designation',AdminUserDesignationCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/user/crud/modify/designation/{admin_user_id}',[AdminUserDesignationCrudController::class,'index']);
    Route::post('hrm/user/crud/modify/designation/list',[AdminUserDesignationCrudController::class,'list']);
    Route::post('hrm/user/crud/modify/designation/delete-list',[AdminUserDesignationCrudController::class,'deleteList']);
    Route::post('hrm/user/crud/modify/designation/update-list',[AdminUserDesignationCrudController::class,'updateList']);
    //vpx_attach
});
