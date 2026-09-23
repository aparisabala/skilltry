<?php

namespace App\Http\Controllers\Admin\Category\Specialization\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\Specialization\Crud\ValidateStoreLibSpecialization;
use App\Repositories\Admin\Category\Specialization\Crud\ILibSpecializationRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LibSpecializationController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibSpecializationRepository $iLibSpecializationRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.category.specialization';
    }

    /**
     * Index page for libspecialization crud
     *
     * @param integer|string $category
     * @param integer|string $subcategory
     * @param Request $request
     * @return View
     */
    public function create($category,$subcategory,Request $request)
    {
        return $this->index($category,$subcategory,$request);
    }

    public function index($category,$subcategory,Request $request) : View
    {
        $data = $this->iLibSpecializationRepo->index($request,$category,$subcategory);
        $data['lang'] = $this->lang;
        return view('admin.pages.category.specialization.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for libspecialization crud
     *
     * @param Request $request
     * @param integer|string $category
     * @param integer|string $subcategory
     * @return JsonResponse
     */
    public function list(Request $request,$category,$subcategory) : JsonResponse
    {
        return  $this->iLibSpecializationRepo->list($request,$category,$subcategory);
    }

    /**
     * Store procedure for libspecialization crud
     *
     * @param integer|string $category
     * @param integer|string $subcategory
     * @param ValidateStoreLibSpecialization $request
     * @return JsonResponse
     */
    public function store($category,$subcategory,ValidateStoreLibSpecialization $request): JsonResponse
    {
        return $this->iLibSpecializationRepo->store($request,$category,$subcategory);
    }

    /**
     * Index page for view
     *
     * @param integer|string $category
     * @param integer|string $subcategory
     * @param integer|string $specialization
     * @param Request $request
     * @return view
     */
    public function edit($category,$subcategory,$specialization,Request $request) : view
    {
        $data = $this->iLibSpecializationRepo->index($request,$category,$subcategory,$specialization);
        $data['lang'] = $this->lang;
        return view('admin.pages.category.specialization.crud.index', compact('data'));
    }

    /**
     * Update procedure for libspecialization
     *
     * @param Request $request
     * @param integer|string $category
     * @param integer|string $subcategory
     * @param integer|string $specialization
     * @return JsonResponse
     */
    public function update(Request $request, $category, $subcategory, $specialization) : JsonResponse
    {
        return $this->iLibSpecializationRepo->update($request,$category,$subcategory,$specialization);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @param integer|string $category
     * @param integer|string $subcategory
     * @return JsonResponse
     */
     public function deleteList(Request $request,$category,$subcategory) : JsonResponse
    {
       return $this->iLibSpecializationRepo->deleteList($request,$category,$subcategory);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @param integer|string $category
     * @param integer|string $subcategory
     * @return JsonResponse
     */
     public function updateList(Request $request,$category,$subcategory) : JsonResponse
    {
       return $this->iLibSpecializationRepo->updateList($request,$category,$subcategory);
    }

}
