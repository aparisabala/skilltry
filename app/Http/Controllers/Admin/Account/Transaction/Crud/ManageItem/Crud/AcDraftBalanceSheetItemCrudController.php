<?php

namespace App\Http\Controllers\Admin\Account\Transaction\Crud\ManageItem\Crud;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Account\Transaction\Crud\ManageItem\Crud\ValidateStoreAcDraftBalanceSheetItem;
use App\Models\AcDraftBalanceSheet;
use App\Models\AcDraftBalanceSheetItem;
use App\Repositories\Admin\Account\Transaction\Crud\ManageItem\Crud\IAcDraftBalanceSheetItemCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcDraftBalanceSheetItemCrudController extends Controller
{

    use BaseTrait;
    public function __construct(private IAcDraftBalanceSheetItemCrudRepository $iAcDraftBalanceSheetItemCrudRepo)
    {
        $this->middleware(['auth:admin', 'HasAdminUserPassword', 'HasAdminUserAuth']);
        $this->lang = 'admin.account.transaction.crud.manage-item.crud';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });
    }

    /**
     * Index page for acdraftbalancesheetitem crud
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $data = $this->iAcDraftBalanceSheetItemCrudRepo->index($request);
        $data['lang'] = $this->lang;
        $data['draft'] = AcDraftBalanceSheet::find($request->ac_draft_transaction_id);
        $data['total'] = AcDraftBalanceSheetItem::where([['ac_draft_transaction_id', '=', $request->ac_draft_transaction_id]])->sum('amount');
        $data['total_items'] = AcDraftBalanceSheetItem::where([['ac_draft_transaction_id', '=', $request->ac_draft_transaction_id]])->count();
        return view('admin.pages.account.transaction.crud.manage-item.crud.index', compact('data'));
    }

    /**
     * List items for yajra datatable for acdraftbalancesheetitem crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        return  $this->iAcDraftBalanceSheetItemCrudRepo->list($request);
    }

    /**
     * Store procedure for comapany crud
     *
     * @param ValidateStoreAcDraftBalanceSheetItem $request
     * @return JsonResponse
     */
    public function store(ValidateStoreAcDraftBalanceSheetItem $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetItemCrudRepo->store($request);
    }

    /**
     * Index page for view
     *
     * @param integer|string $id
     * @param Request $request
     * @return view
     */
    public function edit($id, Request $request): view
    {
        $data = $this->iAcDraftBalanceSheetItemCrudRepo->index($request, $id);
        $data['lang'] = $this->lang;
        return view('admin.pages.account.transaction.crud.manage-item.crud.index', compact('data'));
    }

    /**
     * Update procedure for acdraftbalancesheetitem
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        return $this->iAcDraftBalanceSheetItemCrudRepo->update($request, $id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteList(Request $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetItemCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateList(Request $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetItemCrudRepo->updateList($request);
    }

    /**
     *  Save account data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveAc(Request $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetItemCrudRepo->saveAc($request);
    }
}
