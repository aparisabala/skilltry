<?php

namespace App\Http\Controllers\Admin\Hrm\Setup\PayComponent\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hrm\Setup\PayComponent\Crud\ValidateStoreHrPayComponent;
use App\Repositories\Admin\Hrm\Setup\PayComponent\Crud\IHrPayComponentCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class HrPayComponentCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IHrPayComponentCrudRepository $iHrPayComponentCrudRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.setup.paycomponent';
    }

    /**
     * Index page for hrpaycomponent crud
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
        $data = $this->iHrPayComponentCrudRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.hrm.setup.paycomponent.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for hrpaycomponent crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iHrPayComponentCrudRepo->list($request);
    }

    /**
     * Store procedure for hrpaycomponent crud
     *
     * @param ValidateStoreHrPayComponent $request
     * @return JsonResponse
     */
    public function store(ValidateStoreHrPayComponent $request): JsonResponse
    {
        return $this->iHrPayComponentCrudRepo->store($request);
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
        $data = $this->iHrPayComponentCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.hrm.setup.paycomponent.crud.index', compact('data'));
    }

    /**
     * Update procedure for hrpaycomponent
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iHrPayComponentCrudRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iHrPayComponentCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iHrPayComponentCrudRepo->updateList($request);
    }

}
