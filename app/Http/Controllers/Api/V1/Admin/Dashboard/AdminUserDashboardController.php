<?php

namespace App\Http\Controllers\Api\V1\Admin\Dashboard;

use App\Http\Controllers\Api\ApiController;
use App\Repositories\Api\V1\Admin\Dashboard\IAdminUserDashboardRepository;
use Illuminate\Http\JsonResponse;

#[\Dedoc\Scramble\Attributes\Group('Dashboard')]
class AdminUserDashboardController extends ApiController
{
    public function __construct(private IAdminUserDashboardRepository $repository) {}

    
    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read dashboard data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->repository->index()]);
    }
}
