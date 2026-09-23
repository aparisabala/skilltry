<?php

use App\Http\Controllers\Admin\Hrm\Staff\Education\Crud\HrEducationCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/education',HrEducationCrudController::class)->except(['destroy', 'show']);
    Route::get('hrm/staff/education/{admin_user_id}',[HrEducationCrudController::class,'index'])->whereNumber('admin_user_id');
    Route::post('hrm/staff/education/list',[HrEducationCrudController::class,'list']);
    Route::post('hrm/staff/education/delete-list',[HrEducationCrudController::class,'deleteList']);
    Route::post('hrm/staff/education/update-list',[HrEducationCrudController::class,'updateList']);
    //vpx_attach
});
