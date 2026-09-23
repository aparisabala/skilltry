<?php

use App\Http\Controllers\Admin\Hrm\Staff\Holiday\Crud\HrHolidayCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('hrm/staff/holiday',HrHolidayCrudController::class)->except(['destroy', 'show']);
    Route::post('hrm/staff/holiday/list',[HrHolidayCrudController::class,'list']);
    Route::post('hrm/staff/holiday/delete-list',[HrHolidayCrudController::class,'deleteList']);
    Route::post('hrm/staff/holiday/update-list',[HrHolidayCrudController::class,'updateList']);
    //vpx_attach
});
