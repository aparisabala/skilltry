<?php

namespace App\Http\Controllers\Admin\Hrm\User\Policy\Modal\UpdatePolicyItem;
use App\Http\Controllers\Controller;
use App\Traits\BaseTrait;
use Illuminate\Http\JsonResponse;
use View;
use Illuminate\Http\Request;
use App\Repositories\Admin\Hrm\User\Policy\Modal\UpdatePolicyItem\IUpdatePolicyItemRepository;
//vpx_imports

class UpdatePolicyItemModalController extends Controller {

    use BaseTrait;
    public function __construct(private IUpdatePolicyItemRepository $iUpdatePolicyItemRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.user.policy.modal.update-policy-item';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });

    }

    /**
     * Loaded page for updatepolicyitem
     *
     * @param Request $request
     * @return View
     */
    public function display(Request $request) : JsonResponse
    {
        $data['lang'] = $this->lang;
        $data = [...$data,...$this->iUpdatePolicyItemRepo->display($request)];
        $view = View::make('admin.pages.hrm.user.policy.modal.update-policy-item._modal', compact('data'))->render();
        $response = ['extraData' => ['inflate' => pxLang($data['lang'],'','common.response_success')],'view' => $view];
        return $this->response(['type' => 'success', 'data' => $response]);
    }
    //vpx_attach

}
