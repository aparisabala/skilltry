<?php

namespace App\Http\Controllers\Admin\Hrm\Staff\Employment\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hrm\Staff\Employment\Crud\ValidateHrEmploymentStore;
use App\Models\AdminUser;
use App\Repositories\Admin\Hrm\Staff\Employment\Crud\IHrEmploymentCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
class HrEmploymentCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IHrEmploymentCrudRepository $iHrEmploymentCrudRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.staff.employment.crud';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });

    }

    /**
     * Index page for employment crud
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        return $this->index($request);
    }

    public function index(Request $request) : View | RedirectResponse
    {
        $data = $this->iHrEmploymentCrudRepo->index($request);
        $data['lang'] = $this->lang;
        $data['employee'] = AdminUser::with(['role'])->find($request->admin_user_id);
        if(!$data['employee']) {
            return redirect('admin/hrm/user/user-list/1');
        }
        $data['admin_user_id'] = $request->admin_user_id;
        return view('admin.pages.hrm.staff.employment.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for employment crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iHrEmploymentCrudRepo->list($request);
    }

    /**
     * Store procedure for employment crud
     *
     * @param ValidateHrEmploymentStore $request
     * @return JsonResponse
     */
    public function store(ValidateHrEmploymentStore $request): JsonResponse
    {
        return $this->iHrEmploymentCrudRepo->store($request);
    }

    /**
     * Index page for view
     *
     * @param integer|string $id
     * @param Request $request
     * @return view
     */
    public function edit($id,Request $request) : view
    {
        $data = $this->iHrEmploymentCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        $data['employee'] = AdminUser::with(['role'])->find($data['item']?->admin_user_id);
        $data['admin_user_id'] = $data['item']?->admin_user_id;
        return view('admin.pages.hrm.staff.employment.crud.index', compact('data'));
    }

    /**
     * Update procedure for employment
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iHrEmploymentCrudRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iHrEmploymentCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iHrEmploymentCrudRepo->updateList($request);
    }

}
