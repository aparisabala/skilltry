<?php

use App\Http\Controllers\Admin\DataLibrary\District\Crud\LibDistrictController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/location/district',LibDistrictController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/location/district/list',[LibDistrictController::class,'list']);
    Route::post('datalibrary/location/district/delete-list',[LibDistrictController::class,'deleteList']);
    Route::post('datalibrary/location/district/update-list',[LibDistrictController::class,'updateList']);
    //vpx_attach
});
