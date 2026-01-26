<?php

use App\Http\Controllers\Admin\System\User\Crud\AdminUserCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('system/user',AdminUserCrudController::class)->except(['destroy', 'show']);
    Route::post('system/user/list',[AdminUserCrudController::class,'list']);
    Route::post('system/user/delete-list',[AdminUserCrudController::class,'deleteList']);
    Route::post('system/user/update-list',[AdminUserCrudController::class,'updateList']);
    //vpx_attach
});
