<?php

namespace App\Http\Controllers\Admin\DataLibrary\Skill\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataLibrary\Skill\Crud\ValidateStoreLibSkill;
use App\Repositories\Admin\DataLibrary\Skill\Crud\ILibSkillRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LibSkillController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibSkillRepository $iLibSkillRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.datalibrary.skill';
    }

    /**
     * Index page for libskill crud
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
        $data = $this->iLibSkillRepo->index($request);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.skill.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for libskill crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iLibSkillRepo->list($request);
    }

    /**
     * Store procedure for libskill crud
     *
     * @param ValidateStoreLibSkill $request
     * @return JsonResponse
     */
    public function store(ValidateStoreLibSkill $request): JsonResponse
    {
        return $this->iLibSkillRepo->store($request);
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
        $data = $this->iLibSkillRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.datalibrary.skill.crud.index', compact('data'));
    }

    /**
     * Update procedure for libskill
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iLibSkillRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iLibSkillRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iLibSkillRepo->updateList($request);
    }

}
