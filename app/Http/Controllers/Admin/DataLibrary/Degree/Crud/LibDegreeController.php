<?php

namespace App\Http\Controllers\Admin\DataLibrary\Degree\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataLibrary\Degree\Crud\ValidateStoreLibDegree;
use App\Repositories\Admin\DataLibrary\Degree\Crud\ILibDegreeRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LibDegreeController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibDegreeRepository $iLibDegreeRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.datalibrary.degree';
    }

    /**
     * Index page for libdegree crud
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
        $data = $this->iLibDegreeRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.degree.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for libdegree crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iLibDegreeRepo->list($request);
    }

    /**
     * Store procedure for libdegree crud
     *
     * @param ValidateStoreLibDegree $request
     * @return JsonResponse
     */
    public function store(ValidateStoreLibDegree $request): JsonResponse
    {
        return $this->iLibDegreeRepo->store($request);
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
        $data = $this->iLibDegreeRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.degree.crud.index', compact('data'));
    }

    /**
     * Update procedure for libdegree
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iLibDegreeRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iLibDegreeRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iLibDegreeRepo->updateList($request);
    }

}
