<?php

use App\Http\Controllers\Admin\Account\Report\Cashbook\Load\AccountCashbook\AccountCashbookLoadController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function () {
    Route::get('account/report/cashbook/account-cashbook', [AccountCashbookLoadController::class, 'index']);
    Route::post('account/report/cashbook/account-cashbook/display', [AccountCashbookLoadController::class, 'display']);
    //vpx_attach
});
