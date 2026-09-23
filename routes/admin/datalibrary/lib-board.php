<?php

use App\Http\Controllers\Admin\DataLibrary\Board\Crud\LibBoardController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/board',LibBoardController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/board/list',[LibBoardController::class,'list']);
    Route::post('datalibrary/board/delete-list',[LibBoardController::class,'deleteList']);
    Route::post('datalibrary/board/update-list',[LibBoardController::class,'updateList']);
    //vpx_attach
});
