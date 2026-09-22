<?php

namespace App\Repositories\Api\V1\Admin\Reset;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface IAdminUserResetRepository
{
    public function index(Request $request): JsonResponse;
    public function sendCode(Request $request): JsonResponse;
    public function verifyCode(Request $request): JsonResponse;
    public function changePass(Request $request): JsonResponse;
}
