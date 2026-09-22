<?php

use App\Http\Controllers\Api\V1\Admin\Auth\AdminApiAuthController;
use App\Http\Middleware\Api\AuthenticateAdminApi;
use App\Http\Middleware\Api\ForceJsonResponse;
use Illuminate\Support\Facades\Route;

// Token issuance is public. All web mirrors require admin_api.
Route::prefix('v1/admin/auth')->name('api.v1.admin.auth.')->group(function () {
    Route::post('login', [AdminApiAuthController::class, 'login'])
        ->middleware([ForceJsonResponse::class, 'throttle:10,1'])->name('login');
    Route::middleware(AuthenticateAdminApi::class.':allow-setup')->group(function () {
        Route::get('me', [AdminApiAuthController::class, 'me'])->name('me');
        Route::post('logout', [AdminApiAuthController::class, 'logout'])->name('logout');
    });
});

Route::get('user', [AdminApiAuthController::class, 'me'])
    ->middleware(AuthenticateAdminApi::class.':allow-setup');

Route::get('v1', fn () => response()->json(['success' => true, 'data' => 'Hello World']))
    ->middleware(AuthenticateAdminApi::class);
