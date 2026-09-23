<?php

namespace App\Http\Controllers\Admin\DataLibrary\Department\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataLibrary\Department\Crud\ValidateStoreLibDepartment;
use App\Repositories\Admin\DataLibrary\Department\Crud\ILibDepartmentRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LibDepartmentController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibDepartmentRepository $iLibDepartmentRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.datalibrary.department';
    }

    /**
     * Index page for libdepartment crud
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
        $data = $this->iLibDepartmentRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.department.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for libdepartment crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iLibDepartmentRepo->list($request);
    }

    /**
     * Store procedure for libdepartment crud
     *
     * @param ValidateStoreLibDepartment $request
     * @return JsonResponse
     */
    public function store(ValidateStoreLibDepartment $request): JsonResponse
    {
        return $this->iLibDepartmentRepo->store($request);
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
        $data = $this->iLibDepartmentRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.department.crud.index', compact('data'));
    }

    /**
     * Update procedure for libdepartment
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iLibDepartmentRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iLibDepartmentRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iLibDepartmentRepo->updateList($request);
    }

}
