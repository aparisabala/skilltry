<?php

use App\Http\Controllers\Admin\DataLibrary\Skill\Crud\LibSkillController;
use Illuminate\Support\Facades\Route;
//vpx_imports
Route::prefix('admin')->group(function(){
    Route::resource('datalibrary/skill',LibSkillController::class)->except(['destroy', 'show']);
    Route::post('datalibrary/skill/list',[LibSkillController::class,'list']);
    Route::post('datalibrary/skill/delete-list',[LibSkillController::class,'deleteList']);
    Route::post('datalibrary/skill/update-list',[LibSkillController::class,'updateList']);
    //vpx_attach
});
