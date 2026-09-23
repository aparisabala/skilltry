<?php

use App\Http\Controllers\Admin\DataLibrary\Degree\Crud\LibDegreeController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/degree',LibDegreeController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/degree/list',[LibDegreeController::class,'list']);
    Route::post('datalibrary/degree/delete-list',[LibDegreeController::class,'deleteList']);
    Route::post('datalibrary/degree/update-list',[LibDegreeController::class,'updateList']);
    //vpx_attach
});
