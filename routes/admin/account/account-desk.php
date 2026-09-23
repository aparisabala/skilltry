<?php

use App\Http\Controllers\Admin\Account\Desk\AccountReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/account')->group(function () {
    // reports
    Route::get('reports', [AccountReportController::class, 'index']);
    Route::get('reports/{key}', [AccountReportController::class, 'show']);
    Route::get('reports/{key}/export', [AccountReportController::class, 'export']);
});
