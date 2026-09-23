<?php

/*
 * API mirror of routes/admin/account/ac-draft-balance-sheet-item-crud.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 */

use App\Http\Controllers\Api\V1\Admin\Account\Transaction\Crud\ManageItem\Crud\AcDraftBalanceSheetItemCrudController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function () {
    Route::resource('account/transaction/crud/manage-item', AcDraftBalanceSheetItemCrudController::class)->except(['destroy', 'show']);
    Route::get('account/transaction/crud/manage-item/{ac_draft_transaction_id}', [AcDraftBalanceSheetItemCrudController::class, 'index']);
    Route::post('account/transaction/crud/manage-item/list', [AcDraftBalanceSheetItemCrudController::class, 'list']);
    Route::post('account/transaction/crud/manage-item/delete-list', [AcDraftBalanceSheetItemCrudController::class, 'deleteList']);
    Route::post('account/transaction/crud/manage-item/update-list', [AcDraftBalanceSheetItemCrudController::class, 'updateList']);
    Route::post('account/transaction/crud/manage-item/save', [AcDraftBalanceSheetItemCrudController::class, 'saveAc']);

    //vpx_attach
});
