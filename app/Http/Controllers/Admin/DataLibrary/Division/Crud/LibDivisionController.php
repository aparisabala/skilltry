<?php

namespace App\Http\Controllers\Admin\DataLibrary\Division\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataLibrary\Division\Crud\ValidateStoreLibDivision;
use App\Repositories\Admin\DataLibrary\Division\Crud\ILibDivisionRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LibDivisionController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibDivisionRepository $iLibDivisionRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.datalibrary.location.division';
    }

    /**
     * Index page for libdivision crud
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
        $data = $this->iLibDivisionRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.location.division.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for libdivision crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iLibDivisionRepo->list($request);
    }

    /**
     * Store procedure for libdivision crud
     *
     * @param ValidateStoreLibDivision $request
     * @return JsonResponse
     */
    public function store(ValidateStoreLibDivision $request): JsonResponse
    {
        return $this->iLibDivisionRepo->store($request);
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
        $data = $this->iLibDivisionRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.location.division.crud.index', compact('data'));
    }

    /**
     * Update procedure for libdivision
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iLibDivisionRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iLibDivisionRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iLibDivisionRepo->updateList($request);
    }

}
