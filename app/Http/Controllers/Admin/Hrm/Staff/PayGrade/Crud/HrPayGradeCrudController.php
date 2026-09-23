<?php

namespace App\Http\Controllers\Admin\Hrm\Staff\PayGrade\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hrm\Staff\PayGrade\Crud\ValidateStoreHrPayGrade;
use App\Repositories\Admin\Hrm\Staff\PayGrade\Crud\IHrPayGradeCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class HrPayGradeCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IHrPayGradeCrudRepository $iHrPayGradeCrudRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.staff.paygrade.crud';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });
    }

    /**
     * Index page for paygrade crud
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
        $data = $this->iHrPayGradeCrudRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.hrm.staff.paygrade.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for paygrade crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iHrPayGradeCrudRepo->list($request);
    }

    /**
     * Store procedure for paygrade crud
     *
     * @param ValidateStoreHrPayGrade $request
     * @return JsonResponse
     */
    public function store(ValidateStoreHrPayGrade $request): JsonResponse
    {
        return $this->iHrPayGradeCrudRepo->store($request);
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
        $data = $this->iHrPayGradeCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.hrm.staff.paygrade.crud.index', compact('data'));
    }

    /**
     * Update procedure for paygrade
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iHrPayGradeCrudRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iHrPayGradeCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iHrPayGradeCrudRepo->updateList($request);
    }

}
