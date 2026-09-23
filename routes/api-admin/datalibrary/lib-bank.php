<?php

/*
 * API mirror of routes/admin/datalibrary/lib-bank.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\DataLibrary\Bank\Crud\LibBankController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/bank',LibBankController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/bank/list',[LibBankController::class,'list']);
    Route::post('datalibrary/bank/delete-list',[LibBankController::class,'deleteList']);
    Route::post('datalibrary/bank/update-list',[LibBankController::class,'updateList']);
    //vpx_attach
});
