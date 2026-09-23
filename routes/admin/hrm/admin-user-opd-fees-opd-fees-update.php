<?php

use App\Http\Controllers\Admin\Hrm\User\Crud\Modify\OpdFees\Form\Update\AdminUserOpdFeesOpdFeesUpdateController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::get('hrm/user/crud/modify/opd-fees/update/{admin_user_id}',[AdminUserOpdFeesOpdFeesUpdateController::class,'index']);
    Route::post('hrm/user/crud/modify/opd-fees/update',[AdminUserOpdFeesOpdFeesUpdateController::class,'update']);
    //vpx_attach
});
