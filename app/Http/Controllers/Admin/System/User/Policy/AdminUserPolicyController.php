<?php

namespace App\Http\Controllers\Admin\System\User\Policy;

use App\Http\Controllers\Controller;
use App\Models\AdminUserRole;
use App\Repositories\Admin\System\User\Policy\IAdminUserPolicyRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminUserPolicyController extends Controller {

    use BaseTrait;
    public function __construct(private IAdminUserPolicyRepository $iAdminUserPolicy) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.system.user.policy';
    }

    /**
     * View user policy setup
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request) : View
    {
        $data = $this->iAdminUserPolicy->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.system.user.policy.index')->with('data',$data);
    }

    /**
     * Update system policies
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
       return $this->iAdminUserPolicy->update($request);
    }
}
