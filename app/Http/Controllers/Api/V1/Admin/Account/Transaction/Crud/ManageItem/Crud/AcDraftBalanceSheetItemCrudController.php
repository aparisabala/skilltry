<?php

namespace App\Http\Controllers\Api\V1\Admin\Account\Transaction\Crud\ManageItem\Crud;

use App\Http\Controllers\Api\ApiController as Controller;
use App\Http\Requests\Admin\Account\Transaction\Crud\ManageItem\Crud\ValidateStoreAcDraftBalanceSheetItem;
use App\Models\AcDraftBalanceSheet;
use App\Models\AcDraftBalanceSheetItem;
use App\Repositories\Admin\Account\Transaction\Crud\ManageItem\Crud\IAcDraftBalanceSheetItemCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[\Dedoc\Scramble\Attributes\Group('Account / Transaction')]
class AcDraftBalanceSheetItemCrudController extends Controller
{

    use BaseTrait;
    public function __construct(private IAcDraftBalanceSheetItemCrudRepository $iAcDraftBalanceSheetItemCrudRepo)
    {
        $this->lang = 'admin.account.transaction.crud.manage-item.crud';
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read create-form data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function create(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read draft transaction items data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(Request $request): JsonResponse
    {
        $data = $this->iAcDraftBalanceSheetItemCrudRepo->index($request);
        $data['lang'] = $this->lang;
        $data['draft'] = AcDraftBalanceSheet::find($request->ac_draft_transaction_id);
        $data['total'] = AcDraftBalanceSheetItem::where([['ac_draft_transaction_id', '=', $request->ac_draft_transaction_id]])->sum('amount');
        $data['total_items'] = AcDraftBalanceSheetItem::where([['ac_draft_transaction_id', '=', $request->ac_draft_transaction_id]])->count();
        return response()->json(['success' => true, 'data' => $data]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'List records (DataTables)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ac_draft_transaction_id', type: 'int', required: true)]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{draw: int, recordsTotal: int, recordsFiltered: int, data: array<array<string, mixed>>}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function list(Request $request): JsonResponse
    {
        return  $this->iAcDraftBalanceSheetItemCrudRepo->list($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Create draft transaction line item')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ac_draft_transaction_id', type: 'int', required: true)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('folio_number', type: 'string', required: true)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('description', type: 'string', required: true)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('amount', type: 'int', required: true)]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function store(ValidateStoreAcDraftBalanceSheetItem $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetItemCrudRepo->store($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read edit-form data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function edit($id, Request $request): JsonResponse
    {
        $data = $this->iAcDraftBalanceSheetItemCrudRepo->index($request, $id);
        $data['lang'] = $this->lang;
        return response()->json(['success' => true, 'data' => $data]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Update draft transaction line item')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('folio_number', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('description', type: 'string', required: false)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('amount', type: 'int', required: false)]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function update(Request $request, $id): JsonResponse
    {
        return $this->iAcDraftBalanceSheetItemCrudRepo->update($request, $id);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Delete selected line items')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function deleteList(Request $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetItemCrudRepo->deleteList($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Bulk update selected line items')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function updateList(Request $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetItemCrudRepo->updateList($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Save all line items into the real ledgers (final save)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ac_draft_transaction_id', type: 'int', required: true)]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function saveAc(Request $request): JsonResponse
    {
        return $this->iAcDraftBalanceSheetItemCrudRepo->saveAc($request);
    }
}
