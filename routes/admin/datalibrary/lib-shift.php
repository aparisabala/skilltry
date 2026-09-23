<?php

use App\Http\Controllers\Admin\DataLibrary\Shift\Crud\LibShiftController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/shift',LibShiftController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/shift/list',[LibShiftController::class,'list']);
    Route::post('datalibrary/shift/delete-list',[LibShiftController::class,'deleteList']);
    Route::post('datalibrary/shift/update-list',[LibShiftController::class,'updateList']);
    //vpx_attach
});
