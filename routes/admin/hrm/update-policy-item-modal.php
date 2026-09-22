<?php

use App\Http\Controllers\Admin\Hrm\User\Policy\Modal\UpdatePolicyItem\UpdatePolicyItemModalController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::post('hrm/user/policy/update-policy-item/display',[UpdatePolicyItemModalController::class,'display']);
    //vpx_attach
});