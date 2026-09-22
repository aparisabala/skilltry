<?php

namespace App\Http\Controllers\Api\V1\Admin\Reset;

use App\Http\Controllers\Api\ApiController as Controller;
use App\Repositories\Api\V1\Admin\Reset\IAdminUserResetRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

#[\Dedoc\Scramble\Attributes\Group('Account reset')]
class AdminUserResetController extends Controller
{
    public function __construct(private IAdminUserResetRepository $repository) {}

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read account reset data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(Request $request): JsonResponse
    {
        return $this->repository->index($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Email reset code to token owner')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('email', type: 'string', required: false, description: 'Defaults to token owner; another email is rejected')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function sendCode(Request $request): JsonResponse
    {
        return $this->repository->sendCode($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Verify reset code')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('code', type: 'string', required: true, description: 'Numeric code received by email')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('user_uuid', type: 'string', required: false, description: 'Defaults to token owner; another UUID is rejected')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function verifyCode(Request $request): JsonResponse
    {
        return $this->repository->verifyCode($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Set token owner password')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('password', type: 'string', required: true, description: 'At least 8 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('confirm_password', type: 'string', required: true, description: 'Must match password')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('user_uuid', type: 'string', required: false, description: 'Defaults to token owner; another UUID is rejected')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function changePass(Request $request): JsonResponse
    {
        return $this->repository->changePass($request);
    }
}
