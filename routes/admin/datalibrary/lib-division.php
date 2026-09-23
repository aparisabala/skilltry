<?php

use App\Http\Controllers\Admin\DataLibrary\Division\Crud\LibDivisionController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/location/division',LibDivisionController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/location/division/list',[LibDivisionController::class,'list']);
    Route::post('datalibrary/location/division/delete-list',[LibDivisionController::class,'deleteList']);
    Route::post('datalibrary/location/division/update-list',[LibDivisionController::class,'updateList']);
    //vpx_attach
});
