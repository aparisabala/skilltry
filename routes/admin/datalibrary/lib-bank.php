<?php

use App\Http\Controllers\Admin\DataLibrary\Bank\Crud\LibBankController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/bank',LibBankController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/bank/list',[LibBankController::class,'list']);
    Route::post('datalibrary/bank/delete-list',[LibBankController::class,'deleteList']);
    Route::post('datalibrary/bank/update-list',[LibBankController::class,'updateList']);
    //vpx_attach
});
