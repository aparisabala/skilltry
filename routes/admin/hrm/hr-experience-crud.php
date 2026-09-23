<?php

use App\Http\Controllers\Admin\Hrm\Staff\Experience\Crud\HrExperienceCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/experience',HrExperienceCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/staff/experience/{admin_user_id}',[HrExperienceCrudController::class,'index'])->whereNumber('admin_user_id');
    Route::post('hrm/staff/experience/list',[HrExperienceCrudController::class,'list']);
    Route::post('hrm/staff/experience/delete-list',[HrExperienceCrudController::class,'deleteList']);
    Route::post('hrm/staff/experience/update-list',[HrExperienceCrudController::class,'updateList']);
    //vpx_attach
});
