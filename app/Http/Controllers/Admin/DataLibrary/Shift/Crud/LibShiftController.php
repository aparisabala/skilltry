<?php

namespace App\Http\Controllers\Admin\DataLibrary\Shift\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataLibrary\Shift\Crud\ValidateStoreLibShift;
use App\Repositories\Admin\DataLibrary\Shift\Crud\ILibShiftRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LibShiftController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibShiftRepository $iLibShiftRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.datalibrary.shift';
    }

    /**
     * Index page for libshift crud
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
        $data = $this->iLibShiftRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.shift.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for libshift crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iLibShiftRepo->list($request);
    }

    /**
     * Store procedure for libshift crud
     *
     * @param ValidateStoreLibShift $request
     * @return JsonResponse
     */
    public function store(ValidateStoreLibShift $request): JsonResponse
    {
        return $this->iLibShiftRepo->store($request);
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
        $data = $this->iLibShiftRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.shift.crud.index', compact('data'));
    }

    /**
     * Update procedure for libshift
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iLibShiftRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iLibShiftRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iLibShiftRepo->updateList($request);
    }

}
