<?php

use App\Http\Controllers\Api\V1\Admin\Auth\AdminApiAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('logout', [AdminApiAuthController::class, 'logout'])->name('admin.logout');
});
