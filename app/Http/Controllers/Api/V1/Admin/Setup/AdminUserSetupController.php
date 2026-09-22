<?php

namespace App\Http\Controllers\Api\V1\Admin\Setup;

use App\Http\Controllers\Api\ApiController as Controller;
use App\Repositories\Api\V1\Admin\Setup\IAdminUserSetupRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

#[\Dedoc\Scramble\Attributes\Group('Profile setup')]
class AdminUserSetupController extends Controller
{
    public function __construct(private IAdminUserSetupRepository $repository) {}

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read profile setup data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(409, description: 'Invalid role or setup already completed', type: 'array{success: bool, message: string, redirect: string}')]
    public function index(Request $request): JsonResponse
    {
        return $this->repository->index($request);
    }

    /** @requestMediaType multipart/form-data */
    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Update record')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('name', type: 'string', required: true, description: '')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('email', type: 'string', required: true, description: '')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('mobile_number', type: 'string', required: true, description: '')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('img_uploaded', type: 'string', required: false, description: 'Use no when an image upload is required')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('image', type: 'string', format: 'binary', required: false, description: 'Required when img_uploaded=no; maximum 2024 KB')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('new_password', type: 'string', required: true, description: 'At least 8 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('confim_password', type: 'string', required: true, description: 'Must match new_password; field spelling retained from the existing API')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function update(Request $request): JsonResponse
    {
        return $this->repository->update($request);
    }
}
