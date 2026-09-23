<?php

namespace App\Repositories\Admin\Hrm\User\Crud\Modify\OpdFees\Form\Update;

use App\Http\Requests\Admin\Hrm\User\Crud\Modify\OpdFees\Form\Update\ValidateAdminUserOpdFeesOpdFeesUpdate;
use App\Models\AdminUserOpdFees;
use App\Repositories\BaseRepository;
use App\Traits\BaseTrait;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use DB;
class  AdminUserOpdFeesOpdFeesUpdateRepository extends BaseRepository implements IAdminUserOpdFeesOpdFeesUpdateRepository {

    use BaseTrait;
    public function __construct() {
        $this->LoadModels(['AdminUserOpdFees']);
    }

    /**
     * Get the page default resource
     *
     * @param Request $request
     * @param integer|string $id
     * @return array
     */
    public function index($request, $id=null) : array
    {
        $this->saveTractAction(
            $this->getTrackData(
                title: 'AdminUserOpdFees update was viewed by '.$request?->auth?->name.' at '.Carbon::now()->format('d M Y H:i:s A'),
                request: $request,
                onlyTitle: true
            )
        );
       return $this->getPageDefault(model: $this->AdminUserOpdFees, id: $id);
    }

    /**
     * Update resource
     *
     * @param Requets $request
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update($request) : JsonResponse
    {
        $row = AdminUserOpdFees::find($request->id);
        if(empty($row)){
            return  $this->response(['type' => 'noUpdate', 'title' =>  '<span class="text-danger">'.pxLang($request->lang,'','common.no_resourse').'</span>']);
        }
        $rowRef = [...$row->toArray()];
        $row->fill($request->all());
        if($row->isDirty()){
            $validator = Validator::make($request->all(), (new ValidateAdminUserOpdFeesOpdFeesUpdate())->rules($request,$row));
            if ($validator->fails()) {
                return $this->response(['type' => 'validation','errors' => $validator->errors()]);
            }
            DB::beginTransaction();
            try {
                $row->save();
                $data['extraData'] = ["inflate" =>  pxLang($request->lang,'','common.action_success')];
                $this->saveTractAction($this->getTrackData(title: " AdminUserOpdFees ".$row?->id.' was updated by '.$request?->auth?->name,request: $request, row: $rowRef, type: 'to'));
                DB::commit();
                return $this->response(['type' => 'success','data' => $data]);
            } catch (\Exception $e) {
                DB::rollback();
                $this->saveError($this->getSystemError(['name'=>'AdminUserOpdFees_update_error']), $e);
                return $this->response(["type"=>"wrong","lang"=>"server_wrong"]);
            }
        } else {
            return $this->response(['type' => 'noUpdate', 'title' =>  '<span class="text-success">'.pxLang($request->lang,'','common.no_change').'</span>']);
        }
    }
}
