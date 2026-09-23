<?php

/*
 * API mirror of routes/admin/datalibrary/lib-skill.php
 * Loaded by RouteServiceProvider under /api/v1 with the admin_api guard.
 * Dedicated API controllers reuse repository interfaces and return JSON.
 */

use App\Http\Controllers\Api\V1\Admin\DataLibrary\Skill\Crud\LibSkillController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/skill',LibSkillController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/skill/list',[LibSkillController::class,'list']);
    Route::post('datalibrary/skill/delete-list',[LibSkillController::class,'deleteList']);
    Route::post('datalibrary/skill/update-list',[LibSkillController::class,'updateList']);
    //vpx_attach
});
