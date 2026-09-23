<?php

/*
 * API mirror of routes/admin/account/account-desk.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 */

use App\Http\Controllers\Api\V1\Admin\Account\Desk\AccountReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/account')->group(function () {
    Route::get('reports', [AccountReportController::class, 'index']);
    Route::get('reports/{key}', [AccountReportController::class, 'show']);
    Route::get('reports/{key}/export', [AccountReportController::class, 'export']);
});
