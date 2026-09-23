<?php

namespace App\Http\Controllers\Admin\Hrm\User\Crud\Modify\DoctorOpdSlot\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hrm\User\Crud\Modify\DoctorOpdSlot\Crud\ValidateStoreAdminUserOpdSlot;
use App\Models\AdminUser;
use App\Repositories\Admin\Hrm\User\Crud\Modify\DoctorOpdSlot\Crud\IAdminUserOpdSlotCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
class AdminUserOpdSlotCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IAdminUserOpdSlotCrudRepository $iAdminUserOpdSlotCrudRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.user.crud.modify.doctor-opd-slot.crud';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });

    }

    /**
     * Index page for adminuseropdslot crud
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request) : View | RedirectResponse
    {
        $data = $this->iAdminUserOpdSlotCrudRepo->index($request);
        $data['lang'] = $this->lang;
        $data['doctor'] = AdminUser::with(['role'])->find($request->admin_user_id);
        if(!$data['doctor'] || $data['doctor']?->role?->code != 'DC') {
            return redirect()->route('admin.dashboard.index');
        }
        $data['admin_user_id'] = $request->admin_user_id;
        return view('admin.pages.hrm.user.crud.modify.doctor-opd-slot.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for adminuseropdslot crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iAdminUserOpdSlotCrudRepo->list($request);
    }

    /**
     * Store procedure for adminuseropdslot crud
     *
     * @param ValidateStoreAdminUserOpdSlot $request
     * @return JsonResponse
     */
    public function store(ValidateStoreAdminUserOpdSlot $request): JsonResponse
    {
        return $this->iAdminUserOpdSlotCrudRepo->store($request);
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
        $data = $this->iAdminUserOpdSlotCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        $data['doctor'] = AdminUser::find($data['item']?->admin_user_id);
        $data['admin_user_id'] = $data['item']?->admin_user_id;
        return view('admin.pages.hrm.user.crud.modify.doctor-opd-slot.crud.index', compact('data'));
    }

    /**
     * Update procedure for adminuseropdslot
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iAdminUserOpdSlotCrudRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iAdminUserOpdSlotCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iAdminUserOpdSlotCrudRepo->updateList($request);
    }

}
