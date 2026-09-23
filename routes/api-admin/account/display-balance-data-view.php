<?php

/*
 * API mirror of routes/admin/account/display-balance-data-view.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 */

use App\Http\Controllers\Api\V1\Admin\Account\Report\Balance\DataView\DisplayBalance\DisplayBalanceDataViewController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function () {
    Route::get('account/report/balance/display-balance/display', [DisplayBalanceDataViewController::class, 'index']);
    //vpx_attach
});
