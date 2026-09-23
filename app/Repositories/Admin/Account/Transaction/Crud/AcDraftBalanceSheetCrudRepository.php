<?php

namespace App\Repositories\Admin\Account\Transaction\Crud;

use App\Http\Requests\Admin\Account\Transaction\Crud\ValidateUpdateAcDraftBalanceSheet;
use App\Models\AcDraftBalanceSheet;
use App\Repositories\BaseRepository;
use App\Traits\BaseTrait;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Auth;
use DB;

class AcDraftBalanceSheetCrudRepository extends BaseRepository implements IAcDraftBalanceSheetCrudRepository
{

    use BaseTrait;
    public function __construct()
    {
        $this->LoadModels(['AcDraftBalanceSheet']);
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
        $where = ($id == null) ? [['tran_type', '=', $request->tran_type], ['tran_method', '=', $request->tran_method]] : [];
        return $this->getPageDefault(model: $this->AcDraftBalanceSheet, id: $id, where: $where);
    }


    /**
     * Yajra datatbale list resource
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list($request): JsonResponse
    {
        $model = AcDraftBalanceSheet::with(['debit', 'credit'])->where([['tran_type', '=', $request->tran_type], ['tran_method', '=', $request->tran_method]]);
        $this->saveTractAction(
            $this->getTrackData(
                title: 'AcDraftBalanceSheet was viewed by ' . $request?->auth?->name . ' at ' . Carbon::now()->format('d M Y H:i:s A'),
                request: $request,
                onlyTitle: true
            )
        );
        return DataTables::of($model)
            ->editColumn('created_at', function ($item) {
                return  Carbon::parse($item->created_at)->format('d-m-Y');
            })
            ->editColumn('tran_method', function ($item) {
                return  ucfirst($item->tran_method);;
            })
            ->editColumn('tran_type', function ($item) {
                return  ucfirst($item->tran_type);
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
            $m = AcDraftBalanceSheet::create([
                ...$request->all(),
            ]);
            $response['extraData'] = [
                'inflate' => pxLang($request->lang, '', 'common.action_success'),
                'redirect' => 'admin/account/transaction/crud/manage-item/' . $m?->id
            ];
            $this->saveTractAction($this->getTrackData(title: "AcDraftBalanceSheet was created by " . $request?->auth?->name, request: $request));
            DB::commit();
            return $this->response(['type' => 'success', 'data' => $response]);
        } catch (\Exception $e) {
            DB::rollback();
            $this->saveError($this->getSystemError(['name' => 'AcDraftBalanceSheet_store_error']), $e);
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
        $row = AcDraftBalanceSheet::find($id);
        if (empty($row)) {
            return  $this->response(['type' => 'noUpdate', 'title' =>  '<span class="text-danger">' . pxLang($request->lang, '', 'common.no_resourse') . '</span>']);
        }
        $rowRef = [...$row->toArray()];
        $row->fill($request->all());
        if ($row->isDirty()) {
            $validator = Validator::make($request->all(), (new ValidateUpdateAcDraftBalanceSheet())->rules($request, $row));
            if ($validator->fails()) {
                return $this->response(['type' => 'validation', 'errors' => $validator->errors()]);
            }
            DB::beginTransaction();
            try {
                $row->save();
                $data['extraData'] = ["inflate" =>  pxLang($request->lang, '', 'common.action_success')];
                $this->saveTractAction($this->getTrackData(title: " AcDraftBalanceSheet " . $row?->name . ' was updated by ' . $request?->auth?->name, request: $request, row: $rowRef, type: 'to'));
                DB::commit();
                return $this->response(['type' => 'success', 'data' => $data]);
            } catch (\Exception $e) {
                DB::rollback();
                $this->saveError($this->getSystemError(['name' => 'AcDraftBalanceSheet_update_error']), $e);
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
        $i = AcDraftBalanceSheet::whereIn('id', $request->ids)->select(['id', 'name'])->get();;
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
                    $this->saveTractAction($this->getTrackData(title: "AcDraftBalanceSheet list was updated by " . $request?->auth?->name, request: $request));
                    DB::commit();
                    return $this->response(['type' => 'success', 'data' => $data]);
                } catch (\Exception $e) {
                    DB::rollback();
                    $this->saveError($this->getSystemError(['name' => 'AcDraftBalanceSheet_bulk_update_error']), $e);
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
        $i = AcDraftBalanceSheet::with(['items'])->whereIn('id', $request->ids)->select(['id'])->get();
        if (count($i) > 0) {
            if (count($errors) > 0) {
                return $this->response(['type' => 'bigError', 'errors' => $errors]);
            }
            DB::beginTransaction();
            try {
                foreach ($i as $key => $value) {
                    foreach ($value?->items as $it => $item) {
                        $item->delete();
                    }
                    $value->delete();
                }
                $data['extraData'] = [
                    "inflate" => pxLang($request->lang, '', 'common.action_delete_success'),
                    "redirect" => null
                ];
                $this->saveTractAction($this->getTrackData(title: "AcDraftBalanceSheet list was deleted by " . $request?->auth?->name, request: $request));
                DB::commit();
                return $this->response(['type' => 'success', "data" => $data]);
            } catch (\Exception $e) {
                DB::rollback();
                $this->saveError($this->getSystemError(['name' => 'AcDraftBalanceSheet_store_error']), $e);
                return $this->response(['type' => 'wrong', 'lang' => 'server_wrong']);
            }
        } else {
            return $this->response(['type' => 'noUpdate', 'title' =>  pxLang($request->lang, '', 'common.no_data_selected')]);
        }
    }
}
