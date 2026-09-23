<?php

namespace App\Http\Controllers\Admin\Hrm\User\Crud\Modify\OpdFees\Form\Update;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Repositories\Admin\Hrm\User\Crud\Modify\OpdFees\Form\Update\IAdminUserOpdFeesOpdFeesUpdateRepository;
use App\Traits\BaseTrait;
use App\Models\AdminUserOpdFees;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
class AdminUserOpdFeesOpdFeesUpdateController extends Controller {

    use BaseTrait;
    public function __construct(private IAdminUserOpdFeesOpdFeesUpdateRepository $iAdminUserOpdFeesOpdFeesUpdateRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.user.crud.modify.opd-fees.form.update';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });
    }

    /**
     * View adminuseropdfees update form
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request) : View | RedirectResponse
    {
        $data = $this->iAdminUserOpdFeesOpdFeesUpdateRepo->index($request);
        $data['doctor'] = AdminUser::with(['role','opdFees'])->find($request->admin_user_id);
        $data['lang'] = $this->lang;
        if(!$data['doctor'] || $data['doctor']?->role?->code != 'DC') {
            return redirect()->route('admin.dashboard.index');
        }
        if($data['doctor']?->opdFees == null) {
            AdminUserOpdFees::insert(['admin_user_id' => $data['doctor']?->id]);
            $data['doctor'] = AdminUser::with(['role','opdFees'])->find($request->admin_user_id);
        }
        $data['admin_user_id'] = $request->admin_user_id;
        return view('admin.pages.hrm.user.crud.modify.opd-fees.form.update.index')->with('data',$data);
    }

    /**
     * Update adminuseropdfees form
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request) : JsonResponse
    {
       return $this->iAdminUserOpdFeesOpdFeesUpdateRepo->update($request);
    }
}
