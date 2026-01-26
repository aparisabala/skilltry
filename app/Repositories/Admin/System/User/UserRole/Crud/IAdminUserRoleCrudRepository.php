<?php

namespace App\Repositories\Admin\System\User\UserRole\Crud;

use Illuminate\Http\JsonResponse;

interface IAdminUserRoleCrudRepository {

    public function index($request,$id=null) : array;
    public function list($request) : JsonResponse;
    public function store($request) : JsonResponse;
    public function update($request,$id) : JsonResponse;
    public function updateList($request) : JsonResponse;
    public function deleteList($request) : JsonResponse;


}
