<?php

namespace App\Http\Controllers\Admin\DataLibrary\District\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataLibrary\District\Crud\ValidateStoreLibDistrict;
use App\Repositories\Admin\DataLibrary\District\Crud\ILibDistrictRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LibDistrictController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibDistrictRepository $iLibDistrictRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.datalibrary.location.district';
    }

    /**
     * Index page for libdistrict crud
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
        $data = $this->iLibDistrictRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.location.district.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for libdistrict crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iLibDistrictRepo->list($request);
    }

    /**
     * Store procedure for libdistrict crud
     *
     * @param ValidateStoreLibDistrict $request
     * @return JsonResponse
     */
    public function store(ValidateStoreLibDistrict $request): JsonResponse
    {
        return $this->iLibDistrictRepo->store($request);
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
        $data = $this->iLibDistrictRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.location.district.crud.index', compact('data'));
    }

    /**
     * Update procedure for libdistrict
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iLibDistrictRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iLibDistrictRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iLibDistrictRepo->updateList($request);
    }

}
