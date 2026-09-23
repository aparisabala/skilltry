<?php

namespace App\Repositories\Admin\Hrm\Staff\Experience\Crud;

use Illuminate\Http\JsonResponse;

interface IHrExperienceCrudRepository {

    public function index($request,$id=null) : array;
    public function list($request) : JsonResponse;
    public function store($request) : JsonResponse;
    public function update($request,$id) : JsonResponse;
    public function updateList($request) : JsonResponse;
    public function deleteList($request) : JsonResponse;


}
