<?php

namespace App\Http\Controllers\Admin\Account\Transaction\Crud;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Account\Transaction\Crud\ValidateStoreAcDraftBalanceSheet;
use App\Models\AcLedger;
use App\Repositories\Admin\Account\Transaction\Crud\IAcDraftBalanceSheetCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AcDraftBalanceSheetCrudController extends Controller
{

    use BaseTrait;
    public function __construct(private IAcDraftBalanceSheetCrudRepository $iAcDraftBalanceSheetCrudRepo)
    {
        $this->middleware(['auth:admin', 'HasAdminUserPassword', 'HasAdminUserAuth']);
        $this->lang = 'admin.account.transaction.crud';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });
    }

    /**
     * Index page for acdraftbalancesheet crud
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View | RedirectResponse
    {
        if (!in_array($request->tran_type, ['cash', 'bank']) || !in_array($request->tran_method, ['income', 'expense', 'deposit', 'widraw'])) {
            return redirect()->route('admin.dashboard.index');
        }
        $data = $this->iAcDraftBalanceSheetCrudRepo->index($request);
        $data['lang'] = $this->lang;
        $data = [
            ...$data,
            ...$this->getLedgers($request),
        ];
        return view('admin.pages.account.transaction.crud.index', compact('data'));
    }

    /**
     * List items for yajra datatable for acdraftbalancesheet crud
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        return  $this->iAcDraftBalanceSheetCrudRepo->list($request);
    }

    /**
     * Store procedure for comapany crud
     *
     * @param ValidateStoreAcDraftBalanceSheet $request
     * @return JsonResponse
     */
    public function store(ValidateStoreAcDraftBalanceSheet $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetCrudRepo->store($request);
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
        $data = $this->iAcDraftBalanceSheetCrudRepo->index($request, $id);
        $data['lang'] = $this->lang;
        $data = [
            ...$data,
            ...$this->getLedgers($request, $data['item']),
        ];
        return view('admin.pages.account.transaction.crud.index', compact('data'));
    }

    /**
     * Update procedure for acdraftbalancesheet
     *
     * @param Request $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        return $this->iAcDraftBalanceSheetCrudRepo->update($request, $id);
    }

    /**
     * Bulk delete list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteList(Request $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetCrudRepo->deleteList($request);
    }


    /**
     * Bulk update list resources
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateList(Request $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetCrudRepo->updateList($request);
    }


    /**
     * Get conditional ledgers
     * @param mixed $request
     * @return array<array|\Illuminate\Database\Eloquent\Collection<int, AcLedger>|\Illuminate\Support\Collection<int, \stdClass>>
     */
    public function getLedgers($request, $row = null): array
    {
        $data = [];
        $data['debit_ledgers'] = [];
        $data['credit_ledgers'] = [];

        $type = $request->tran_type;
        $method = $request->tran_method;

        if ($row != null) {
            $type = $row?->tran_type;
            $method = $row?->tran_method;
        }

        $data['tran_type'] = $type;
        $data['tran_method'] = $method;

        if ($type == 'cash' && $method == "income") {
            $data['debit_ledgers'] = AcLedger::whereIn('ledger_type', ['income'])->get();
            $data['credit_ledgers'] = AcLedger::whereIn('ledger_type', ['cash'])->get();
        }

        if ($type == 'cash' && $method == "expense") {
            $data['credit_ledgers'] = AcLedger::whereIn('ledger_type', ['expense'])->get();
            $data['debit_ledgers'] = AcLedger::whereIn('ledger_type', ['cash'])->get();
        }

        if ($type == 'bank' && $method == "income") {
            $data['credit_ledgers'] = AcLedger::whereIn('ledger_type', ['bank'])->get();
            $data['debit_ledgers'] = AcLedger::whereIn('ledger_type', ['income', 'asset'])->get();
        }

        if ($type == 'bank' && $method == "expense") {
            $data['credit_ledgers'] = AcLedger::whereIn('ledger_type', ['expense', 'asset'])->get();
            $data['debit_ledgers'] = AcLedger::whereIn('ledger_type', ['bank'])->get();
        }

        if ($type == 'bank' && $method == "deposit") {
            $data['credit_ledgers'] = AcLedger::whereIn('ledger_type', ['bank'])->get();
            $data['debit_ledgers'] = AcLedger::whereIn('ledger_type', ['cash'])->get();
        }

        if ($type == 'bank' && $method == "widraw") {
            $data['credit_ledgers'] = AcLedger::whereIn('ledger_type', ['cash'])->get();
            $data['debit_ledgers'] = AcLedger::whereIn('ledger_type', ['bank'])->get();
        }
        return $data;
    }
}
