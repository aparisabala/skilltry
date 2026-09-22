<?php

namespace App\Repositories\Api\V1\Admin\Auth;

use App\Models\AdminUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface IAdminApiAuthRepository
{
    /**
     * Verify credentials and issue a admin_api access token
     */
    public function login(Request $request): JsonResponse;

    /**
     * Profile and permissions of the token owner
     */
    public function me(AdminUser $user): JsonResponse;

    /**
     * Revoke the token used for the current request
     */
    public function logout(AdminUser $user): JsonResponse;
}
