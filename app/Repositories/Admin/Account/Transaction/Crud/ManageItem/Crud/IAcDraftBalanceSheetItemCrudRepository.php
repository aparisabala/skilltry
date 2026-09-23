<?php

namespace App\Repositories\Admin\Account\Transaction\Crud\ManageItem\Crud;

use Illuminate\Http\JsonResponse;

interface IAcDraftBalanceSheetItemCrudRepository
{

    public function index($request, $id = null): array;
    public function list($request): JsonResponse;
    public function store($request): JsonResponse;
    public function update($request, $id): JsonResponse;
    public function updateList($request): JsonResponse;
    public function deleteList($request): JsonResponse;
    public function saveAc($request): JsonResponse;
}
