<?php

namespace App\Http\Controllers\Api\V1\Admin\Account\Transaction\Crud;

use App\Http\Controllers\Api\ApiController as Controller;
use App\Http\Requests\Admin\Account\Transaction\Crud\ValidateStoreAcDraftBalanceSheet;
use App\Models\AcLedger;
use App\Repositories\Admin\Account\Transaction\Crud\IAcDraftBalanceSheetCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[\Dedoc\Scramble\Attributes\Group('Account / Transaction')]
class AcDraftBalanceSheetCrudController extends Controller
{

    use BaseTrait;
    public function __construct(private IAcDraftBalanceSheetCrudRepository $iAcDraftBalanceSheetCrudRepo)
    {
        $this->lang = 'admin.account.transaction.crud';
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read create-form data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function create(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read draft transaction data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(Request $request): JsonResponse
    {
        if (!in_array($request->tran_type, ['cash', 'bank']) || !in_array($request->tran_method, ['income', 'expense', 'deposit', 'widraw'])) {
            return response()->json(['success' => false, 'message' => 'Unknown transaction type / method'], 422);
        }
        $data = $this->iAcDraftBalanceSheetCrudRepo->index($request);
        $data['lang'] = $this->lang;
        $data = [
            ...$data,
            ...$this->getLedgers($request),
        ];
        return response()->json(['success' => true, 'data' => $data]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'List records (DataTables)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('tran_type', type: 'string', required: true, description: 'cash or bank')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('tran_method', type: 'string', required: true, description: 'income, expense, deposit or widraw')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{draw: int, recordsTotal: int, recordsFiltered: int, data: array<array<string, mixed>>}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function list(Request $request): JsonResponse
    {
        return  $this->iAcDraftBalanceSheetCrudRepo->list($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Create draft transaction')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('tran_date', type: 'string', required: true, description: 'Y-m-d')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('tran_type', type: 'string', required: true)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('tran_method', type: 'string', required: true)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('debit_to', type: 'int', required: true)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('credit_to', type: 'int', required: true)]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function store(ValidateStoreAcDraftBalanceSheet $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetCrudRepo->store($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read edit-form data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function edit($id, Request $request): JsonResponse
    {
        $data = $this->iAcDraftBalanceSheetCrudRepo->index($request, $id);
        $data['lang'] = $this->lang;
        $data = [
            ...$data,
            ...$this->getLedgers($request, $data['item']),
        ];
        return response()->json(['success' => true, 'data' => $data]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Update draft transaction')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('tran_date', type: 'string', required: false, description: 'Y-m-d')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('debit_to', type: 'int', required: false)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('credit_to', type: 'int', required: false)]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function update(Request $request, $id): JsonResponse
    {
        return $this->iAcDraftBalanceSheetCrudRepo->update($request, $id);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Delete selected draft transactions')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function deleteList(Request $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetCrudRepo->deleteList($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Bulk update selected draft transactions')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function updateList(Request $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetCrudRepo->updateList($request);
    }

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
