<?php

namespace App\Http\Controllers\Admin\Logout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\RedirectResponse;
use App\Traits\BaseTrait;
//vpx_imports
class AdminUserLogoutController extends Controller
{
    use BaseTrait;
    public function __construct()
    {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang = 'admin.logout';
    }

    public function logout(Request $request) : RedirectResponse
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login.index')->withErrors(["success" => [0 =>  pxLang($this->lang,'mgs.logout_sucess')]]);
    
    }
    //vpx_attach
}
