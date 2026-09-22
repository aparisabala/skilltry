<?php

namespace App\Http\Controllers\Admin\Hrm\User\UserRole\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hrm\User\UserRole\Crud\ValidateStoreAdminUserRole;
use App\Repositories\Admin\Hrm\User\UserRole\Crud\IAdminUserRoleCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class AdminUserRoleCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IAdminUserRoleCrudRepository $iAdminUserRoleCrudRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.user.user-role';
    }

    /**
     * Index page for adminuserrole crud
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        return $this->index($request);
    }

    public function index(Request $request) : View
    {
        $data = $this->iAdminUserRoleCrudRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.hrm.user.user-role.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for adminuserrole crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iAdminUserRoleCrudRepo->list($request);
    }

    /**
     * Store procedure for comapany crud
     *
     * @param ValidateStoreAdminUserRole $request
     * @return JsonResponse
     */
    public function store(ValidateStoreAdminUserRole $request): JsonResponse
    {
        return $this->iAdminUserRoleCrudRepo->store($request);
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
        $data = $this->iAdminUserRoleCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.hrm.user.user-role.crud.index', compact('data'));
    }

    /**
     * Update procedure for adminuserrole
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iAdminUserRoleCrudRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iAdminUserRoleCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iAdminUserRoleCrudRepo->updateList($request);
    }

}
