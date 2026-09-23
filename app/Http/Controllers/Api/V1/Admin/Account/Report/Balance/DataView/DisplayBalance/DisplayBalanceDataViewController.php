<?php

namespace App\Http\Controllers\Api\V1\Admin\Account\Report\Balance\DataView\DisplayBalance;

use App\Http\Controllers\Api\ApiController as Controller;
use App\Repositories\Admin\Account\Report\Balance\DataView\DisplayBalance\IDisplayBalanceDataViewRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[\Dedoc\Scramble\Attributes\Group('Account / Widgets')]
class DisplayBalanceDataViewController extends Controller
{

    use BaseTrait;
    public function __construct(private IDisplayBalanceDataViewRepository $iDisplayBalanceDataViewRepo)
    {
        $this->lang = 'admin.account.report.balance.data-view.display-balance';
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read cash / bank ledger balances')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response.', type: 'array{success: bool, data: mixed}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(Request $request): JsonResponse
    {
        $data = $this->iDisplayBalanceDataViewRepo->index($request);
        $data['lang'] = $this->lang;
        return response()->json(['success' => true, 'data' => $data]);
    }
}
