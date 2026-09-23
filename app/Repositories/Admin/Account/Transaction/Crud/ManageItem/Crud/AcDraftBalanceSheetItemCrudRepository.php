<?php

namespace App\Repositories\Admin\Account\Transaction\Crud\ManageItem\Crud;

use App\Http\Requests\Admin\Account\Transaction\Crud\ManageItem\Crud\ValidateUpdateAcDraftBalanceSheetItem;
use App\Models\AcBalanceSheet;
use App\Models\AcCashbook;
use App\Models\AcDraftBalanceSheet;
use App\Models\AcDraftBalanceSheetItem;
use App\Models\AcTranItem;
use App\Repositories\BaseRepository;
use App\Traits\BaseTrait;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Auth;
use DB;

class AcDraftBalanceSheetItemCrudRepository extends BaseRepository implements IAcDraftBalanceSheetItemCrudRepository
{

    use BaseTrait;
    public function __construct()
    {
        $this->LoadModels(['AcDraftBalanceSheetItem']);
    }

    /**
     * Get the page default resource
     *
     * @param Request $request
     * @param integer|string $id
     * @return array
     */
    public function index($request, $id = null): array
    {
        $where = ($id == null) ? [['ac_draft_transaction_id', '=', $request->ac_draft_transaction_id]] : [];
        return $this->getPageDefault(model: $this->AcDraftBalanceSheetItem, id: $id, where: $where);
    }


    /**
     * Yajra datatbale list resource
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list($request): JsonResponse
    {
        $model = AcDraftBalanceSheetItem::with(['draft'])->where([['ac_draft_transaction_id', '=', $request->ac_draft_transaction_id]]);
        $this->saveTractAction(
            $this->getTrackData(
                title: 'AcDraftBalanceSheetItem was viewed by ' . $request?->auth?->name . ' at ' . Carbon::now()->format('d M Y H:i:s A'),
                request: $request,
                onlyTitle: true
            )
        );
        return DataTables::of($model)
            ->editColumn('created_at', function ($item) {
                return  Carbon::parse($item->created_at)->format('d-m-Y');
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store resource
     *
     * @param Request  $request
     * @return JsonResponse
     */
    public function store($request): JsonResponse
    {
        DB::beginTransaction();
        try {
            AcDraftBalanceSheetItem::create([
                ...$request->all(),
            ]);
            $response['extraData'] = ['inflate' => pxLang($request->lang, '', 'common.action_success')];
            $this->saveTractAction($this->getTrackData(title: "AcDraftBalanceSheetItem was created by " . $request?->auth?->name, request: $request));
            DB::commit();
            return $this->response(['type' => 'success', 'data' => $response]);
        } catch (\Exception $e) {
            DB::rollback();
            $this->saveError($this->getSystemError(['name' => 'AcDraftBalanceSheetItem_store_error']), $e);
            return $this->response(['type' => 'noUpdate', 'title' => pxLang($request->lang, '', 'common.server_wrong')]);
        }
    }

    /**
     * Update resource
     *
     * @param Requets $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update($request, $id): JsonResponse
    {
        $row = AcDraftBalanceSheetItem::find($id);
        if (empty($row)) {
            return  $this->response(['type' => 'noUpdate', 'title' =>  '<span class="text-danger">' . pxLang($request->lang, '', 'common.no_resourse') . '</span>']);
        }
        $rowRef = [...$row->toArray()];
        $row->fill($request->all());
        if ($row->isDirty()) {
            $validator = Validator::make($request->all(), (new ValidateUpdateAcDraftBalanceSheetItem())->rules($request, $row));
            if ($validator->fails()) {
                return $this->response(['type' => 'validation', 'errors' => $validator->errors()]);
            }
            DB::beginTransaction();
            try {
                $row->save();
                $data['extraData'] = ["inflate" =>  pxLang($request->lang, '', 'common.action_success')];
                $this->saveTractAction($this->getTrackData(title: " AcDraftBalanceSheetItem " . $row?->name . ' was updated by ' . $request?->auth?->name, request: $request, row: $rowRef, type: 'to'));
                DB::commit();
                return $this->response(['type' => 'success', 'data' => $data]);
            } catch (\Exception $e) {
                DB::rollback();
                $this->saveError($this->getSystemError(['name' => 'AcDraftBalanceSheetItem_update_error']), $e);
                return $this->response(["type" => "wrong", "lang" => "server_wrong"]);
            }
        } else {
            return $this->response(['type' => 'noUpdate', 'title' =>  '<span class="text-success">' . pxLang($request->lang, '', 'common.no_change') . '</span>']);
        }
    }

    /**
     *  Bulk update list resource
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateList($request): JsonResponse
    {
        $i = AcDraftBalanceSheetItem::whereIn('id', $request->ids)->select(['id', 'name'])->get();;
        $dirty = [];
        if (count($i) > 0) {
            foreach ($i as $key => $value) {
                if ($value->isDirty()) {
                    $dirty[$key] = "yes";
                }
            }
            if (count($dirty) > 0) {
                DB::beginTransaction();
                try {
                    foreach ($i as $key => $value) {
                        $value->save();
                    }
                    $data['extraData'] = [
                        "inflate" => pxLang($request->lang, '', 'common.action_update_success')
                    ];
                    $this->saveTractAction($this->getTrackData(title: "AcDraftBalanceSheetItem list was updated by " . $request?->auth?->name, request: $request));
                    DB::commit();
                    return $this->response(['type' => 'success', 'data' => $data]);
                } catch (\Exception $e) {
                    DB::rollback();
                    $this->saveError($this->getSystemError(['name' => 'AcDraftBalanceSheetItem_bulk_update_error']), $e);
                    return $this->response(['type' => 'wrong', 'lang' => 'server_wrong']);
                }
            } else {
                return $this->response(['type' => 'noUpdate', 'title' =>  '<span class="text-success"> ' . pxLang($request->lang, '', 'common.no_change') . '  </span>']);
            }
        } else {
            return $this->response(['type' => 'noUpdate', 'title' => pxLang($request->lang, '', 'common.went_wrong')]);
        }
    }

    /**
     * Bulk delete list resource
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteList($request): JsonResponse
    {
        $errors = [];
        $i = AcDraftBalanceSheetItem::whereIn('id', $request->ids)->select(['id'])->get();
        if (count($i) > 0) {
            if (count($errors) > 0) {
                return $this->response(['type' => 'bigError', 'errors' => $errors]);
            }
            DB::beginTransaction();
            try {
                foreach ($i as $key => $value) {
                    $value->delete();
                }
                $data['extraData'] = [
                    "inflate" => pxLang($request->lang, '', 'common.action_delete_success'),
                    "redirect" => null
                ];
                $this->saveTractAction($this->getTrackData(title: "AcDraftBalanceSheetItem list was deleted by " . $request?->auth?->name, request: $request));
                DB::commit();
                return $this->response(['type' => 'success', "data" => $data]);
            } catch (\Exception $e) {
                DB::rollback();
                $this->saveError($this->getSystemError(['name' => 'AcDraftBalanceSheetItem_store_error']), $e);
                return $this->response(['type' => 'wrong', 'lang' => 'server_wrong']);
            }
        } else {
            return $this->response(['type' => 'noUpdate', 'title' =>  pxLang($request->lang, '', 'common.no_data_selected')]);
        }
    }

    /**
     *  Save account data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveAc($request): JsonResponse
    {
        $draft = AcDraftBalanceSheet::with(['items'])->find($request->ac_draft_transaction_id);
        if (empty($draft)) {
            return $this->response(['type' => 'noUpdate', 'title' => 'Draft transation not found,try agian']);
        }

        if (($draft->tran_type == 'bank' && $draft->tran_method == 'deposit') || ($draft->tran_type == 'bank' && $draft->tran_method == 'widraw')) {
            return $this->contraEntry($draft, $request);
        } else {
            return $this->generalEntry($draft, $request);
        }
    }

    /**
     * Cash / bank income or expense: the draft goes into the books through AccountPosting (a cashbook row and a balance sheet row that
     * are linked, the line items on both, an expense never taking more than the ledger holds), all or nothing.
     */
    private function generalEntry($draft, $request)
    {
        $draftCopy = $draft;
        $lc = $this->getLergers($request, $draft);
        $cbLedger = $lc['cbLedger'];
        $bsLedger = $lc['bsLedger'];
        if (empty($cbLedger) || $bsLedger == null) {
            return $this->response(['type' => 'noUpdate', 'title' => 'Invalid ledger defination, try again']);
        }
        DB::beginTransaction();
        try {
            $draft = AcDraftBalanceSheet::with('items')->lockForUpdate()->find($draft->id);
            if (empty($draft)) {
                DB::rollback();
                return $this->response(['type' => 'noUpdate', 'title' => 'This transaction was already saved']);
            }
            $r = \App\Support\Account\AccountPosting::post([
                'tran_type' => $draft->tran_type, 'tran_method' => $draft->tran_method, 'cash_ledger_id' => $cbLedger, 'counter_ledger_id' => $bsLedger, 'tran_date' => $draft->tran_date,
                'items' => $draft->items->map(fn($i) => ['folio_number' => $i->folio_number, 'description' => $i->description, 'amount' => $i->amount])->all(),
            ]);
            if (!$r['ok']) {
                DB::rollback();
                return $this->response(['type' => 'noUpdate', 'title' => $r['error']]);
            }
            foreach ($draft->items as $value) {
                $value->delete();
            }
            $draft->delete();
            $response['extraData'] = [
                'inflate' => pxLang($request->lang, '', 'common.action_success'),
                'redirect' => 'admin/account/transaction/' . $draftCopy?->tran_type . '/' . $draftCopy?->tran_method
            ];
            DB::commit();
            return $this->response(['type' => 'success', 'data' => $response]);
        } catch (\Exception $e) {
            DB::rollback();
            $this->saveError($this->getSystemError(['name' => 'UqProfession_store_error']), $e);
            return $this->response(['type' => 'wrong', 'lang' => 'server_wrong']);
        }
    }

    /**
     * Deposit / withdraw between a cash and a bank ledger. On this form the ledger in `debit_to` gives the money and the one in
     * `credit_to` receives it (a deposit before put the money into the cash ledger and took it out of the bank: it was the wrong way round).
     */
    public function contraEntry($draft, $request)
    {
        $draftCopy = $draft;
        DB::beginTransaction();
        try {
            $draft = AcDraftBalanceSheet::with('items')->lockForUpdate()->find($draft->id);
            if (empty($draft)) {
                DB::rollback();
                return $this->response(['type' => 'noUpdate', 'title' => 'This transaction was already saved']);
            }
            $r = \App\Support\Account\AccountPosting::contra([
                'from_ledger_id' => $draft->debit_to, 'to_ledger_id' => $draft->credit_to, 'tran_date' => $draft->tran_date,
                'items' => $draft->items->map(fn($i) => ['folio_number' => $i->folio_number, 'description' => $i->description, 'amount' => $i->amount])->all(),
            ]);
            if (!$r['ok']) {
                DB::rollback();
                return $this->response(['type' => 'noUpdate', 'title' => $r['error']]);
            }
            foreach ($draft->items as $value) {
                $value->delete();
            }
            $draft->delete();
            $response['extraData'] = [
                'inflate' => pxLang($request->lang, '', 'common.action_success'),
                'redirect' => 'admin/account/transaction/' . $draftCopy?->tran_type . '/' . $draftCopy?->tran_method
            ];
            DB::commit();
            return $this->response(['type' => 'success', 'data' => $response]);
        } catch (\Exception $e) {
            DB::rollback();
            $this->saveError($this->getSystemError(['name' => 'UqProfession_store_error']), $e);
            return $this->response(['type' => 'wrong', 'lang' => 'server_wrong']);
        }
    }


    private function getLergers($request, $draft)
    {
        $cbLedger = null;
        $bsLedger = null;
        if ($draft?->tran_type == "cash" && $draft?->tran_method == "income") {
            $cbLedger = $draft?->credit_to;
            $bsLedger = $draft?->debit_to;
        }
        if ($draft?->tran_type == "cash" && $draft?->tran_method == "expense") {
            $cbLedger = $draft?->debit_to;
            $bsLedger = $draft?->credit_to;
        }

        if ($draft?->tran_type == "bank" && $draft?->tran_method == "income") {
            $cbLedger = $draft?->credit_to;
            $bsLedger = $draft?->debit_to;
        }

        if ($draft?->tran_type == "bank" && $draft?->tran_method == "expense") {
            $cbLedger = $draft?->debit_to;
            $bsLedger = $draft?->credit_to;
        }

        return [
            'cbLedger' => $cbLedger,
            'bsLedger' => $bsLedger,
        ];
    }
}
