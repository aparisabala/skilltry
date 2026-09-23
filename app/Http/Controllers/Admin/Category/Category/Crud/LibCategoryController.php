<?php

namespace App\Http\Controllers\Admin\Category\Category\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\Category\Crud\ValidateStoreLibCategory;
use App\Repositories\Admin\Category\Category\Crud\ILibCategoryRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LibCategoryController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibCategoryRepository $iLibCategoryRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.category.category';
    }

    /**
     * Index page for libcategory crud
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
        $data = $this->iLibCategoryRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.category.category.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for libcategory crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iLibCategoryRepo->list($request);
    }

    /**
     * Store procedure for libcategory crud
     *
     * @param ValidateStoreLibCategory $request
     * @return JsonResponse
     */
    public function store(ValidateStoreLibCategory $request): JsonResponse
    {
        return $this->iLibCategoryRepo->store($request);
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
        $data = $this->iLibCategoryRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.category.category.crud.index', compact('data'));
    }

    /**
     * Update procedure for libcategory
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iLibCategoryRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iLibCategoryRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iLibCategoryRepo->updateList($request);
    }

}
