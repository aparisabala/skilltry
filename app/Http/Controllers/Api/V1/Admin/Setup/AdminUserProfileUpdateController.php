<?php

namespace App\Http\Controllers\Api\V1\Admin\Setup;

use App\Http\Controllers\Api\ApiController as Controller;
use App\Repositories\Api\V1\Admin\Setup\IAdminUserProfileUpdateRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

#[\Dedoc\Scramble\Attributes\Group('Profile setup')]
class AdminUserProfileUpdateController extends Controller
{
    public function __construct(private IAdminUserProfileUpdateRepository $repository) {}

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read profile setup data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(Request $request): JsonResponse
    {
        return $this->repository->index($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read password-change data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function password(Request $request): JsonResponse
    {
        return $this->repository->password($request);
    }

    /** @requestMediaType multipart/form-data */
    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Update your profile')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('name', type: 'string', required: true, description: '')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('email', type: 'string', required: true, description: '')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('mobile_number', type: 'string', required: true, description: '')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('img_uploaded', type: 'string', required: false, description: 'Use no when an image upload is required')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('image', type: 'string', format: 'binary', required: false, description: 'Required when img_uploaded=no; maximum 2024 KB')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function updateProfile(Request $request): JsonResponse
    {
        return $this->repository->updateProfile($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Change your password')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('old_password', type: 'string', required: true, description: 'At least 8 characters; confirmation must match password')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('password', type: 'string', required: true, description: 'At least 8 characters; confirmation must match password')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('confirm_password', type: 'string', required: true, description: 'At least 8 characters; confirmation must match password')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function updatePassword(Request $request): JsonResponse
    {
        return $this->repository->updatePassword($request);
    }
}
