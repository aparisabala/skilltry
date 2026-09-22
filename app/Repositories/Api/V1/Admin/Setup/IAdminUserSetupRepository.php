<?php

namespace App\Repositories\Api\V1\Admin\Setup;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface IAdminUserSetupRepository
{
    public function index(Request $request): JsonResponse;
    public function update(Request $request): JsonResponse;
}
