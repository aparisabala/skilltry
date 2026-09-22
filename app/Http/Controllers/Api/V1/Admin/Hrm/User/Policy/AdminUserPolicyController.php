<?php

namespace App\Http\Controllers\Api\V1\Admin\Hrm\User\Policy;

use App\Http\Controllers\Api\ApiController as Controller;
use App\Models\AdminUserRole;
use App\Repositories\Admin\Hrm\User\Policy\IAdminUserPolicyRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

#[\Dedoc\Scramble\Attributes\Group('HR permissions')]
class AdminUserPolicyController extends Controller {

    use BaseTrait;
    public function __construct(private IAdminUserPolicyRepository $iAdminUserPolicy) {

        $this->lang= 'admin.hrm.user.policy';
    }

    
    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read hr permissions data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(Request $request) : JsonResponse
    {
        $data = $this->iAdminUserPolicy->index($request);
        $data['lang'] = $this->lang;
        return response()->json(['success' => true, 'data' => $data]);
    }

    
    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Update record')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('slug', type: 'int[]', required: true, description: 'Permission row IDs from the policy response')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('user_access', type: 'array<int, string[]>', required: false, description: 'Map each permission ID to role codes. Omitted selections clear access.')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function update(Request $request): JsonResponse
    {
       return $this->iAdminUserPolicy->update($request);
    }
}
