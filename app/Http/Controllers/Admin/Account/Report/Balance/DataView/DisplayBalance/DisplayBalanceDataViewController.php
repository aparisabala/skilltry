<?php

namespace App\Http\Controllers\Admin\Account\Report\Balance\DataView\DisplayBalance;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Account\Report\Balance\DataView\DisplayBalance\IDisplayBalanceDataViewRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DisplayBalanceDataViewController extends Controller
{

    use BaseTrait;
    public function __construct(private IDisplayBalanceDataViewRepository $iDisplayBalanceDataViewRepo)
    {
        $this->middleware(['auth:admin', 'HasAdminUserPassword', 'HasAdminUserAuth']);
        $this->lang = 'admin.account.report.balance.data-view.display-balance';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });
    }

    /**
     * Index page for displaybalance crud
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $data = $this->iDisplayBalanceDataViewRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.account.report.balance.data-view.display-balance.index', compact('data'));
    }
}
