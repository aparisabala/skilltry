<?php

namespace App\Http\Controllers\Admin\DataLibrary\Board\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataLibrary\Board\Crud\ValidateStoreLibBoard;
use App\Repositories\Admin\DataLibrary\Board\Crud\ILibBoardRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LibBoardController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibBoardRepository $iLibBoardRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.datalibrary.board';
    }

    /**
     * Index page for libboard crud
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
        $data = $this->iLibBoardRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.board.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for libboard crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iLibBoardRepo->list($request);
    }

    /**
     * Store procedure for libboard crud
     *
     * @param ValidateStoreLibBoard $request
     * @return JsonResponse
     */
    public function store(ValidateStoreLibBoard $request): JsonResponse
    {
        return $this->iLibBoardRepo->store($request);
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
        $data = $this->iLibBoardRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.board.crud.index', compact('data'));
    }

    /**
     * Update procedure for libboard
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iLibBoardRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iLibBoardRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iLibBoardRepo->updateList($request);
    }

}
