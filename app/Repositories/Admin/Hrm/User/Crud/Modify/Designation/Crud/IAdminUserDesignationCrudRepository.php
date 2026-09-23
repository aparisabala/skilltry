<?php

namespace App\Repositories\Admin\Hrm\User\Crud\Modify\Designation\Crud;

use Illuminate\Http\JsonResponse;

interface IAdminUserDesignationCrudRepository {

    public function index($request,$id=null) : array;
    public function list($request) : JsonResponse;
    public function store($request) : JsonResponse;
    public function update($request,$id) : JsonResponse;
    public function updateList($request) : JsonResponse;
    public function deleteList($request) : JsonResponse;


}
