<?php

namespace App\Repositories\Admin\Category\Subcategory\Crud;

use Illuminate\Http\JsonResponse;

interface ILibSubcategoryRepository {

    public function index($request,$categoryId,$id=null) : array;
    public function list($request,$categoryId) : JsonResponse;
    public function store($request,$categoryId) : JsonResponse;
    public function update($request,$categoryId,$id) : JsonResponse;
    public function updateList($request,$categoryId) : JsonResponse;
    public function deleteList($request,$categoryId) : JsonResponse;


}
