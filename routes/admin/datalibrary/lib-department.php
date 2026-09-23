<?php

use App\Http\Controllers\Admin\DataLibrary\Department\Crud\LibDepartmentController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/department',LibDepartmentController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/department/list',[LibDepartmentController::class,'list']);
    Route::post('datalibrary/department/delete-list',[LibDepartmentController::class,'deleteList']);
    Route::post('datalibrary/department/update-list',[LibDepartmentController::class,'updateList']);
    //vpx_attach
});
