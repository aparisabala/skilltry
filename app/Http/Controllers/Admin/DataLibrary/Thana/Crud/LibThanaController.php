<?php

namespace App\Http\Controllers\Admin\DataLibrary\Thana\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataLibrary\Thana\Crud\ValidateStoreLibThana;
use App\Repositories\Admin\DataLibrary\Thana\Crud\ILibThanaRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LibThanaController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibThanaRepository $iLibThanaRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.datalibrary.location.thana';
    }

    /**
     * Index page for libthana crud
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
        $data = $this->iLibThanaRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.location.thana.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for libthana crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iLibThanaRepo->list($request);
    }

    /**
     * Store procedure for libthana crud
     *
     * @param ValidateStoreLibThana $request
     * @return JsonResponse
     */
    public function store(ValidateStoreLibThana $request): JsonResponse
    {
        return $this->iLibThanaRepo->store($request);
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
        $data = $this->iLibThanaRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.location.thana.crud.index', compact('data'));
    }

    /**
     * Update procedure for libthana
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iLibThanaRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iLibThanaRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iLibThanaRepo->updateList($request);
    }

}
