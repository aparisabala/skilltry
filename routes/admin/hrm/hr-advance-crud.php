<?php

use App\Http\Controllers\Admin\Hrm\Staff\Advance\Crud\HrAdvanceCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/advance',HrAdvanceCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/staff/advance/{admin_user_id}',[HrAdvanceCrudController::class,'index'])->whereNumber('admin_user_id');
    Route::post('hrm/staff/advance/list',[HrAdvanceCrudController::class,'list']);
    Route::post('hrm/staff/advance/delete-list',[HrAdvanceCrudController::class,'deleteList']);
    Route::post('hrm/staff/advance/update-list',[HrAdvanceCrudController::class,'updateList']);
    //vpx_attach
});
