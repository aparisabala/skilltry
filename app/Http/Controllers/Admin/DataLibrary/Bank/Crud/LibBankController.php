<?php

namespace App\Http\Controllers\Admin\DataLibrary\Bank\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataLibrary\Bank\Crud\ValidateStoreLibBank;
use App\Repositories\Admin\DataLibrary\Bank\Crud\ILibBankRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LibBankController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibBankRepository $iLibBankRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.datalibrary.bank';
    }

    /**
     * Index page for libbank crud
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
        $data = $this->iLibBankRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.bank.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for libbank crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iLibBankRepo->list($request);
    }

    /**
     * Store procedure for libbank crud
     *
     * @param ValidateStoreLibBank $request
     * @return JsonResponse
     */
    public function store(ValidateStoreLibBank $request): JsonResponse
    {
        return $this->iLibBankRepo->store($request);
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
        $data = $this->iLibBankRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.bank.crud.index', compact('data'));
    }

    /**
     * Update procedure for libbank
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iLibBankRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iLibBankRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iLibBankRepo->updateList($request);
    }

}
