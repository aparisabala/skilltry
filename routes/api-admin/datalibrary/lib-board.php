<?php

/*
 * API mirror of routes/admin/datalibrary/lib-board.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\DataLibrary\Board\Crud\LibBoardController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/board',LibBoardController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/board/list',[LibBoardController::class,'list']);
    Route::post('datalibrary/board/delete-list',[LibBoardController::class,'deleteList']);
    Route::post('datalibrary/board/update-list',[LibBoardController::class,'updateList']);
    //vpx_attach
});
