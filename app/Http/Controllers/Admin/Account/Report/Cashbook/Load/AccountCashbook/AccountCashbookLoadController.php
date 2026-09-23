<?php

namespace App\Http\Controllers\Admin\Account\Report\Cashbook\Load\AccountCashbook;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Account\Report\Cashbook\Load\AccountCashbook\IAccountCashbookLoadRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View as ReturnView;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use View;

class AccountCashbookLoadController extends Controller
{

    use BaseTrait;
    public function __construct(private IAccountCashbookLoadRepository $iAccountCashbookLoadRepo)
    {
        $this->middleware(['auth:admin', 'HasAdminUserPassword', 'HasAdminUserAuth']);
        $this->lang = 'admin.account.report.cashbook.load';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });
    }

    /**
     * Index page for accountcashbook crud
     *
     * @param Request $request
     * @return ReturnView
     */
    public function index(Request $request): ReturnView
    {
        $data = $this->iAccountCashbookLoadRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.account.report.cashbook.load.account-cashbook.index', compact('data'));
    }

    /**
     * Load view
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function display(Request $request): JsonResponse
    {
        $data['lang'] = $this->lang;
        $data = [...$data, ...$this->iAccountCashbookLoadRepo->display($request)];
        $view = View::make('admin.pages.account.report.cashbook.load.account-cashbook.fragments._display', compact('data'))->render();
        $response = ['extraData' => ['inflate' => pxLang($data['lang'], '', 'common.response_success')], 'view' => $view];
        return $this->response(['type' => 'success', 'data' => $response]);
    }
}
