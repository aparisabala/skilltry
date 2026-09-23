<?php

use App\Http\Controllers\Admin\Hrm\Staff\PayComponent\Crud\HrPayComponentCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/pay-component',HrPayComponentCrudController::class)->except(['destroy', 'show']);
    Route::post('hrm/staff/pay-component/list',[HrPayComponentCrudController::class,'list']);
    Route::post('hrm/staff/pay-component/delete-list',[HrPayComponentCrudController::class,'deleteList']);
    Route::post('hrm/staff/pay-component/update-list',[HrPayComponentCrudController::class,'updateList']);
    //vpx_attach
});
