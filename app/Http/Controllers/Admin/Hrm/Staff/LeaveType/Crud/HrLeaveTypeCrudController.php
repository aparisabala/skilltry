<?php

namespace App\Http\Controllers\Admin\Hrm\Staff\LeaveType\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hrm\Staff\LeaveType\Crud\ValidateStoreHrLeaveType;
use App\Repositories\Admin\Hrm\Staff\LeaveType\Crud\IHrLeaveTypeCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class HrLeaveTypeCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IHrLeaveTypeCrudRepository $iHrLeaveTypeCrudRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.staff.leavetype.crud';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });
    }

    /**
     * Index page for leavetype crud
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
        $data = $this->iHrLeaveTypeCrudRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.hrm.staff.leavetype.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for leavetype crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iHrLeaveTypeCrudRepo->list($request);
    }

    /**
     * Store procedure for leavetype crud
     *
     * @param ValidateStoreHrLeaveType $request
     * @return JsonResponse
     */
    public function store(ValidateStoreHrLeaveType $request): JsonResponse
    {
        return $this->iHrLeaveTypeCrudRepo->store($request);
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
        $data = $this->iHrLeaveTypeCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.hrm.staff.leavetype.crud.index', compact('data'));
    }

    /**
     * Update procedure for leavetype
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iHrLeaveTypeCrudRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iHrLeaveTypeCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iHrLeaveTypeCrudRepo->updateList($request);
    }

}
