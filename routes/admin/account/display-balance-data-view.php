<?php

use App\Http\Controllers\Admin\Account\Report\Balance\DataView\DisplayBalance\DisplayBalanceDataViewController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function () {
    Route::get('account/report/balance/display-balance/display', [DisplayBalanceDataViewController::class, 'index']);
    //vpx_attach
});
