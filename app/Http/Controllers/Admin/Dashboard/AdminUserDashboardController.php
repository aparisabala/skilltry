<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Contracts\View\View;
use App\Traits\BaseTrait;
//vpx_imports
class AdminUserDashboardController extends Controller
{
    use BaseTrait;
    public function __construct()
    {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
    }

    /**
     * View AdminUser dashboard page
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request) : View
    {
        return view('admin.pages.dashboard.index');
    }
    //vpx_attach
}
