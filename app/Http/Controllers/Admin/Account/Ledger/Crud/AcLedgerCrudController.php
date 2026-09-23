<?php

namespace App\Http\Controllers\Admin\Account\Ledger\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Account\Ledger\Crud\ValidateStoreAcLedger;
use App\Repositories\Admin\Account\Ledger\Crud\IAcLedgerCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
class AcLedgerCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IAcLedgerCrudRepository $iAcLedgerCrudRepo) {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->lang= 'admin.account.ledger.crud';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });

    }

    /**
     * Index page for acledger crud
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request) : View | RedirectResponse
    {
        if(!in_array($request->ledger_type,['income','expense','cash','bank','asset'])) {
            return redirect()->route('admin.dashboard.index');
        }
        $data = $this->iAcLedgerCrudRepo->index($request);
        $data['lang'] = $this->lang;
        $data['ledger_type'] = $request->ledger_type;
        return view('admin.pages.account.ledger.crud.index',compact('data'));
    }

    /**
     * List items for yajra datatable for acledger crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request) : JsonResponse
    {
        return  $this->iAcLedgerCrudRepo->list($request);
    }

    /**
     * Store procedure for comapany crud
     *
     * @param ValidateStoreAcLedger $request
     * @return JsonResponse
     */
    public function store(ValidateStoreAcLedger $request): JsonResponse
    {
        return $this->iAcLedgerCrudRepo->store($request);
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
        $data = $this->iAcLedgerCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return view('admin.pages.account.ledger.crud.index', compact('data'));
    }

    /**
     * Update procedure for acledger
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iAcLedgerCrudRepo->update($request,$id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function deleteList(Request $request) : JsonResponse
    {
       return $this->iAcLedgerCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
     public function updateList(Request $request) : JsonResponse
    {
       return $this->iAcLedgerCrudRepo->updateList($request);
    }

}
