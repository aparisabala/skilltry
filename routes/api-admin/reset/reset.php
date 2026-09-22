<?php

use App\Http\Controllers\Api\V1\Admin\Reset\AdminUserResetController;
use App\Http\Middleware\Api\ResetAuthenticatedAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->withoutMiddleware('guest:admin')->middleware(ResetAuthenticatedAdmin::class)->group(function () {
    Route::get('reset', [AdminUserResetController::class, 'index'])->name('admin.reset');
    Route::post('reset/send-code', [AdminUserResetController::class, 'sendCode']);
    Route::post('reset/verify-code', [AdminUserResetController::class, 'verifyCode']);
    Route::post('reset/change-pass', [AdminUserResetController::class, 'changePass']);
});
