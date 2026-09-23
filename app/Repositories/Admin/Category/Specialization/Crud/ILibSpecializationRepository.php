<?php

namespace App\Repositories\Admin\Category\Specialization\Crud;

use Illuminate\Http\JsonResponse;

interface ILibSpecializationRepository {

    public function index($request,$categoryId,$subcategoryId,$id=null) : array;
    public function list($request,$categoryId,$subcategoryId) : JsonResponse;
    public function store($request,$categoryId,$subcategoryId) : JsonResponse;
    public function update($request,$categoryId,$subcategoryId,$id) : JsonResponse;
    public function updateList($request,$categoryId,$subcategoryId) : JsonResponse;
    public function deleteList($request,$categoryId,$subcategoryId) : JsonResponse;


}
