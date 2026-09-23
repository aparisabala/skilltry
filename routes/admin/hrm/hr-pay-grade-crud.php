<?php

use App\Http\Controllers\Admin\Hrm\Staff\PayGrade\Crud\HrPayGradeCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/pay-grade',HrPayGradeCrudController::class)->except(['destroy', 'show']);
    Route::post('hrm/staff/pay-grade/list',[HrPayGradeCrudController::class,'list']);
    Route::post('hrm/staff/pay-grade/delete-list',[HrPayGradeCrudController::class,'deleteList']);
    Route::post('hrm/staff/pay-grade/update-list',[HrPayGradeCrudController::class,'updateList']);
    //vpx_attach
});
