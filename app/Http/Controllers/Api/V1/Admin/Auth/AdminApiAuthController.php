<?php

namespace App\Http\Controllers\Api\V1\Admin\Auth;

use App\Http\Controllers\Api\ApiController as Controller;
use App\Http\Requests\Api\V1\Admin\Auth\ValidateAdminApiLogin;
use App\Repositories\Api\V1\Admin\Auth\IAdminApiAuthRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[\Dedoc\Scramble\Attributes\Group('Authentication')]
class AdminApiAuthController extends Controller
{
    public function __construct(private IAdminApiAuthRepository $iAdminApiAuthRepo) {}

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read authentication data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => [
            'fields' => ['email', 'password', 'device_name'],
            'token_type' => 'Bearer',
        ]]);
    }

    
    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Issue access token')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data: array{token: string, token_type: string, expires_at: string, setup_required: bool, user: array{id: int, uuid: string, name: string, email: string, mobile_number: string|null, role: array{code: string|null, name: string|null}}}}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function login(ValidateAdminApiLogin $request): JsonResponse
    {
        return $this->iAdminApiAuthRepo->login($request);
    }

    
    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Current administrator and permissions')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function me(Request $request): JsonResponse
    {
        return $this->iAdminApiAuthRepo->me($request->user('admin_api'));
    }

    
    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Revoke current token')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function logout(Request $request): JsonResponse
    {
        return $this->iAdminApiAuthRepo->logout($request->user('admin_api'));
    }
}
