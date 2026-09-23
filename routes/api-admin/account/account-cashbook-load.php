<?php

/*
 * API mirror of routes/admin/account/account-cashbook-load.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 */

use App\Http\Controllers\Api\V1\Admin\Account\Report\Cashbook\Load\AccountCashbook\AccountCashbookLoadController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function () {
    Route::get('account/report/cashbook/account-cashbook', [AccountCashbookLoadController::class, 'index']);
    Route::post('account/report/cashbook/account-cashbook/display', [AccountCashbookLoadController::class, 'display']);
    //vpx_attach
});
