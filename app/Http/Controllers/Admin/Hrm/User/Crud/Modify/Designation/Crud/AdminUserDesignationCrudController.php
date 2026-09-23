<?php

namespace App\Http\Controllers\Admin\Hrm\User\Crud\Modify\Designation\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hrm\User\Crud\Modify\Designation\Crud\ValidateStoreAdminUserDesignation;
use App\Models\AdminUser;
use App\Repositories\Admin\Hrm\User\Crud\Modify\Designation\Crud\IAdminUserDesignationCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
class AdminUserDesignationCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IAdminUserDesignationCrudRepository $iAdminUserDesignationCrudRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.user.crud.modify.designation.crud';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });

    }

    /**
     * Index page for adminuserdesignation crud
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request) : View | RedirectResponse
    {
        $data = $this->iAdminUserDesignationCrudRepo->index($request);
        $data['lang'] = $this->lang;
        $data['doctor'] = AdminUser::with(['role'])->find($request->admin_user_id);
        if(!$data['doctor'] || $data['doctor']?->role?->code != 'DC') {
            return redirect()->route('admin.dashboard.index');
        }
        $data['admin_user_id'] = $request->admin_user_id;
        return view('admin.pages.hrm.user.crud.modify.designation.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for adminuserdesignation crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iAdminUserDesignationCrudRepo->list($request);
    }

    /**
     * Store procedure for adminuserdesignation crud
     *
     * @param ValidateStoreAdminUserDesignation $request
     * @return JsonResponse
     */
    public function store(ValidateStoreAdminUserDesignation $request): JsonResponse
    {
        return $this->iAdminUserDesignationCrudRepo->store($request);
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
        $data = $this->iAdminUserDesignationCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        $data['doctor'] = AdminUser::find($data['item']?->admin_user_id);
        $data['admin_user_id'] = $data['item']?->admin_user_id;
        return view('admin.pages.hrm.user.crud.modify.designation.crud.index', compact('data'));
    }

    /**
     * Update procedure for adminuserdesignation
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iAdminUserDesignationCrudRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iAdminUserDesignationCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iAdminUserDesignationCrudRepo->updateList($request);
    }

}
