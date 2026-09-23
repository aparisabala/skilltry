<?php

namespace App\Http\Controllers\Admin\Category\Subcategory\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\Subcategory\Crud\ValidateStoreLibSubcategory;
use App\Repositories\Admin\Category\Subcategory\Crud\ILibSubcategoryRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LibSubcategoryController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibSubcategoryRepository $iLibSubcategoryRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.category.subcategory';
    }

    /**
     * Index page for libsubcategory crud
     *
     * @param integer|string $category
     * @param Request $request
     * @return View
     */
    public function create($category,Request $request)
    {
        return $this->index($category,$request);
    }

    public function index($category,Request $request) : View
    {
        $data = $this->iLibSubcategoryRepo->index($request,$category);
        $data['lang'] = $this->lang;
        return view('admin.pages.category.subcategory.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for libsubcategory crud
     *
     * @param Request $request
     * @param integer|string $category
     * @return JsonResponse
     */
    public function list(Request $request,$category) : JsonResponse
    {
        return  $this->iLibSubcategoryRepo->list($request,$category);
    }

    /**
     * Store procedure for libsubcategory crud
     *
     * @param integer|string $category
     * @param ValidateStoreLibSubcategory $request
     * @return JsonResponse
     */
    public function store($category,ValidateStoreLibSubcategory $request): JsonResponse
    {
        return $this->iLibSubcategoryRepo->store($request,$category);
    }

    /**
     * Index page for view
     *
     * @param integer|string $category
     * @param integer|string $subcategory
     * @param Request $request
     * @return view
     */
    public function edit($category,$subcategory,Request $request) : view
    {
        $data = $this->iLibSubcategoryRepo->index($request,$category,$subcategory);
        $data['lang'] = $this->lang;
        return view('admin.pages.category.subcategory.crud.index', compact('data'));
    }

    /**
     * Update procedure for libsubcategory
     *
     * @param Request $request
     * @param integer|string $category
     * @param integer|string $subcategory
     * @return JsonResponse
     */
    public function update(Request $request, $category, $subcategory) : JsonResponse
    {
        return $this->iLibSubcategoryRepo->update($request,$category,$subcategory);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @param integer|string $category
     * @return JsonResponse
     */
     public function deleteList(Request $request,$category) : JsonResponse
    {
       return $this->iLibSubcategoryRepo->deleteList($request,$category);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @param integer|string $category
     * @return JsonResponse
     */
     public function updateList(Request $request,$category) : JsonResponse
    {
       return $this->iLibSubcategoryRepo->updateList($request,$category);
    }

}
