<?php

namespace App\Repositories\Api\V1\Admin\Setup;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface IAdminUserProfileUpdateRepository
{
    public function index(Request $request): JsonResponse;
    public function password(Request $request): JsonResponse;
    public function updateProfile(Request $request): JsonResponse;
    public function updatePassword(Request $request): JsonResponse;
}
