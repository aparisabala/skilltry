<?php

namespace App\Http\Controllers\Admin\Hrm\Staff\Experience\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hrm\Staff\Experience\Crud\ValidateHrExperienceStore;
use App\Models\AdminUser;
use App\Repositories\Admin\Hrm\Staff\Experience\Crud\IHrExperienceCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
class HrExperienceCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IHrExperienceCrudRepository $iHrExperienceCrudRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.hrm.staff.experience.crud';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });

    }

    /**
     * Index page for experience crud
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        return $this->index($request);
    }

    public function index(Request $request) : View | RedirectResponse
    {
        $data = $this->iHrExperienceCrudRepo->index($request);
        $data['lang'] = $this->lang;
        $data['employee'] = AdminUser::with(['role'])->find($request->admin_user_id);
        if(!$data['employee']) {
            return redirect('admin/hrm/user/user-list/1');
        }
        $data['admin_user_id'] = $request->admin_user_id;
        return view('admin.pages.hrm.staff.experience.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for experience crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iHrExperienceCrudRepo->list($request);
    }

    /**
     * Store procedure for experience crud
     *
     * @param ValidateHrExperienceStore $request
     * @return JsonResponse
     */
    public function store(ValidateHrExperienceStore $request): JsonResponse
    {
        return $this->iHrExperienceCrudRepo->store($request);
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
        $data = $this->iHrExperienceCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        $data['employee'] = AdminUser::with(['role'])->find($data['item']?->admin_user_id);
        $data['admin_user_id'] = $data['item']?->admin_user_id;
        return view('admin.pages.hrm.staff.experience.crud.index', compact('data'));
    }

    /**
     * Update procedure for experience
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iHrExperienceCrudRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iHrExperienceCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iHrExperienceCrudRepo->updateList($request);
    }

}
