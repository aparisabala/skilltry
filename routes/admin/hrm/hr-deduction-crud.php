<?php

use App\Http\Controllers\Admin\Hrm\Staff\Deduction\Crud\HrDeductionCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/deduction',HrDeductionCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/staff/deduction/{admin_user_id}',[HrDeductionCrudController::class,'index'])->whereNumber('admin_user_id');
    Route::post('hrm/staff/deduction/list',[HrDeductionCrudController::class,'list']);
    Route::post('hrm/staff/deduction/delete-list',[HrDeductionCrudController::class,'deleteList']);
    Route::post('hrm/staff/deduction/update-list',[HrDeductionCrudController::class,'updateList']);
    //vpx_attach
});
