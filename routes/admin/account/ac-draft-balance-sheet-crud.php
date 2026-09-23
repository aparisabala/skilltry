<?php

use App\Http\Controllers\Admin\Account\Transaction\Crud\AcDraftBalanceSheetCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function () {
    Route::resource('account/transaction', AcDraftBalanceSheetCrudController::class)->except(['destroy', 'show']);
    Route::get('account/transaction/{tran_type}/{tran_method}', [AcDraftBalanceSheetCrudController::class, 'index']);
    Route::post('account/transaction/list', [AcDraftBalanceSheetCrudController::class, 'list']);
    Route::post('account/transaction/delete-list', [AcDraftBalanceSheetCrudController::class, 'deleteList']);
    Route::post('account/transaction/update-list', [AcDraftBalanceSheetCrudController::class, 'updateList']);
    //vpx_attach
});
