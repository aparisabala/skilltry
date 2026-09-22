<?php

namespace App\Http\Controllers\Admin\Hrm\User\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hrm\User\Crud\ValidateStoreAdminUser;
use App\Repositories\Admin\Hrm\User\Crud\IAdminUserCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\AdminUserRole;
use Illuminate\Http\RedirectResponse;
class AdminUserCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IAdminUserCrudRepository $iAdminUserCrudRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.user';
    }

     /**
     * Index page for AdminUser crud
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
        $data = $this->iAdminUserCrudRepo->index($request);
        $data['lang'] = $this->lang;
        if(empty(AdminUserRole::select(['id','name','code'])->find($request->admin_user_role_id))) {
            return redirect()->route('admin.dashboard.index');
        }
        $data['admin_user_role_id'] = $request->admin_user_role_id;
        return view('admin.pages.hrm.user.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for AdminUser crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iAdminUserCrudRepo->list($request);
    }

    /**
     * Store procedure for comapany crud
     *
     * @param ValidateStoreAdminUser $request
     * @return JsonResponse
     */
    public function store(ValidateStoreAdminUser $request): JsonResponse
    {
        return $this->iAdminUserCrudRepo->store($request);
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
        $data = $this->iAdminUserCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        $data['userRoles'] = AdminUserRole::select(['id','name','code'])->get();
        return view('admin.pages.hrm.user.crud.index', compact('data'));
    }

    /**
     * Update procedure for AdminUser
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iAdminUserCrudRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iAdminUserCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iAdminUserCrudRepo->updateList($request);
    }
}