<?php

namespace App\Http\Controllers\Admin\Hrm\Staff\Holiday\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hrm\Staff\Holiday\Crud\ValidateStoreHrHoliday;
use App\Repositories\Admin\Hrm\Staff\Holiday\Crud\IHrHolidayCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class HrHolidayCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IHrHolidayCrudRepository $iHrHolidayCrudRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.staff.holiday.crud';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });
    }

    /**
     * Index page for holiday crud
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
        $data = $this->iHrHolidayCrudRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.hrm.staff.holiday.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for holiday crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iHrHolidayCrudRepo->list($request);
    }

    /**
     * Store procedure for holiday crud
     *
     * @param ValidateStoreHrHoliday $request
     * @return JsonResponse
     */
    public function store(ValidateStoreHrHoliday $request): JsonResponse
    {
        return $this->iHrHolidayCrudRepo->store($request);
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
        $data = $this->iHrHolidayCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.hrm.staff.holiday.crud.index', compact('data'));
    }

    /**
     * Update procedure for holiday
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iHrHolidayCrudRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iHrHolidayCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iHrHolidayCrudRepo->updateList($request);
    }

}
