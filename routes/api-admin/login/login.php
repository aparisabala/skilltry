<?php

use App\Http\Controllers\Api\V1\Admin\Auth\AdminApiAuthController;
use Illuminate\Support\Facades\Route;

// Authenticated mirror; initial token issuance is /api/v1/admin/auth/login.
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminApiAuthController::class, 'index'])->name('admin.login.index');
    Route::post('login', [AdminApiAuthController::class, 'login'])->name('admin.login');
});
