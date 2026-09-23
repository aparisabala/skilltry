<?php

/*
 * A consolidated, mobile-friendly account API layer. API-only (no web equivalent to mirror): ledgers with a computed
 * live balance, the ledger statement and day book reports run directly, and a single endpoint to post a
 * receive / pay / deposit / withdraw transaction straight through AccountPosting (no draft step).
 * The per-module admin panel mirrors (account/ledger, account/transaction/...) stay as they are, unaffected.
 */

use App\Http\Controllers\Api\V1\Admin\Account\AccountController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/account')->group(function () {
    Route::get('ledgers', [AccountController::class, 'ledgers']);
    Route::post('ledgers', [AccountController::class, 'storeLedger']);
    Route::get('ledgers/{id}', [AccountController::class, 'showLedger'])->whereNumber('id');
    Route::put('ledgers/{id}', [AccountController::class, 'updateLedger'])->whereNumber('id');
    Route::get('ledgers/{id}/statement', [AccountController::class, 'statement'])->whereNumber('id');
    Route::get('transactions', [AccountController::class, 'transactions']);
    Route::post('transactions', [AccountController::class, 'post']);
});
