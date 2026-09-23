<?php

use App\Http\Controllers\Admin\Account\Ledger\Crud\AcLedgerCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('account/ledger',AcLedgerCrudController::class)->except(['destroy', 'show']);
    Route::get('account/ledger/{ledger_type}',[AcLedgerCrudController::class,'index']);
    Route::post('account/ledger/list',[AcLedgerCrudController::class,'list']);
    Route::post('account/ledger/delete-list',[AcLedgerCrudController::class,'deleteList']);
    Route::post('account/ledger/update-list',[AcLedgerCrudController::class,'updateList']);
    //vpx_attach
});
