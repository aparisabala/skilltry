<?php

namespace App\Http\Controllers\Admin\Hrm\Staff\Education\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hrm\Staff\Education\Crud\ValidateHrEducationStore;
use App\Models\AdminUser;
use App\Repositories\Admin\Hrm\Staff\Education\Crud\IHrEducationCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
class HrEducationCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IHrEducationCrudRepository $iHrEducationCrudRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.staff.education.crud';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });

    }

    /**
     * Index page for education crud
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
        $data = $this->iHrEducationCrudRepo->index($request);
        $data['lang'] = $this->lang;
        $data['employee'] = AdminUser::with(['role'])->find($request->admin_user_id);
        if(!$data['employee']) {
            return redirect('admin/hrm/user/user-list/1');
        }
        $data['admin_user_id'] = $request->admin_user_id;
        return view('admin.pages.hrm.staff.education.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for education crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iHrEducationCrudRepo->list($request);
    }

    /**
     * Store procedure for education crud
     *
     * @param ValidateHrEducationStore $request
     * @return JsonResponse
     */
    public function store(ValidateHrEducationStore $request): JsonResponse
    {
        return $this->iHrEducationCrudRepo->store($request);
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
        $data = $this->iHrEducationCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        $data['employee'] = AdminUser::with(['role'])->find($data['item']?->admin_user_id);
        $data['admin_user_id'] = $data['item']?->admin_user_id;
        return view('admin.pages.hrm.staff.education.crud.index', compact('data'));
    }

    /**
     * Update procedure for education
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iHrEducationCrudRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iHrEducationCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iHrEducationCrudRepo->updateList($request);
    }

}
