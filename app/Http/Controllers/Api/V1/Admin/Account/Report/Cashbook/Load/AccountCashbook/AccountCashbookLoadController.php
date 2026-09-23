<?php

namespace App\Http\Controllers\Api\V1\Admin\Account\Report\Cashbook\Load\AccountCashbook;

use App\Http\Controllers\Api\ApiController as Controller;
use App\Repositories\Admin\Account\Report\Cashbook\Load\AccountCashbook\IAccountCashbookLoadRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[\Dedoc\Scramble\Attributes\Group('Account / Widgets')]
class AccountCashbookLoadController extends Controller
{

    use BaseTrait;
    public function __construct(private IAccountCashbookLoadRepository $iAccountCashbookLoadRepo)
    {
        $this->lang = 'admin.account.report.cashbook.load';
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read create-form data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response.', type: 'array{success: bool, data: mixed}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(Request $request): JsonResponse
    {
        $data = $this->iAccountCashbookLoadRepo->index($request);
        $data['lang'] = $this->lang;
        return response()->json(['success' => true, 'data' => $data]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Load the cashbook data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function display(Request $request): JsonResponse
    {
        $data['lang'] = $this->lang;
        $data = [...$data, ...$this->iAccountCashbookLoadRepo->display($request)];
        return response()->json(['success' => true, 'data' => $data]);
    }
}
