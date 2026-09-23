<?php

namespace App\Repositories\Admin\Category\Specialization\Crud;

use App\Http\Requests\Admin\Category\Specialization\Crud\ValidateUpdateLibSpecialization;
use App\Models\LibCategory;
use App\Models\LibSubcategory;
use App\Models\LibSpecialization;
use App\Repositories\BaseRepository;
use App\Traits\BaseTrait;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Webpatser\Uuid\Uuid;
use DB;
class LibSpecializationRepository extends BaseRepository implements ILibSpecializationRepository {

    use BaseTrait;
    public function __construct() {
        $this->sizes =  [
            ['width'=> 300, 'height'=> 300,'com'=> 60],
        ];
        $this->LoadModels(['LibCategory', 'LibSubcategory', 'LibSpecialization']);
    }

    /**
     * Get the page default resource
     *
     * @param Request $request
     * @param integer|string $categoryId
     * @param integer|string $subcategoryId
     * @param integer|string $id
     * @return array
     */
    public function index($request,$categoryId,$subcategoryId,$id=null) : array
    {
       return [
           'category' => LibCategory::find($categoryId),
           'subcategory' => LibSubcategory::find($subcategoryId),
           ...$this->getPageDefault(model: LibSpecialization::where('lib_subcategory_id',$subcategoryId), id: $id),
       ];
    }


    /**
     * Yajra datatbale list resource
     *
     * @param Request $request
     * @param integer|string $categoryId
     * @param integer|string $subcategoryId
     * @return JsonResponse
     */
    public function list($request,$categoryId,$subcategoryId) : JsonResponse
    {
        $model = LibSpecialization::where('lib_subcategory_id',$subcategoryId);
        $this->saveTractAction(
            $this->getTrackData(
                title: 'LibSpecialization was viewed by '.$request?->auth?->name.' at '.Carbon::now()->format('d M Y H:i:s A'),
                request: $request,
                onlyTitle: true
            )
        );
        return DataTables::of($model)
        ->addColumn('image', function($item) {
            return "<img src='".getRowImage($item, '300X300')."' class='img-fluid' style='max-height:40px'/>";
        })
        ->editColumn('created_at', function($item) {
            return  Carbon::parse($item->created_at)->format('d-m-Y');
        })
        ->escapeColumns([])
        ->make(true);
    }

    /**
     * Store resource
     *
     * @param Request  $request
     * @param integer|string $categoryId
     * @param integer|string $subcategoryId
     * @return JsonResponse
     */
    public function store($request,$categoryId,$subcategoryId) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $m = new LibSpecialization;
            $m->name = $request->name;
            $m->lib_category_id = $categoryId;
            $m->lib_subcategory_id = $subcategoryId;
            $path = imagePaths()['dyn_image'];
            $image = $request->file('image');
            if ($request->hasFile('image')) {
                $image_link = (string) Uuid::generate(4);
                $extension = $image->getClientOriginalExtension();
                $this->imageVersioning([
                    'image' => $image, 'path' => $path, 'image_link' => $image_link, 'extension' => $extension,
                    'appendSize' => true,
                    'onlyAppend' => $this->sizes
                ]);
                $m->image = $image_link;
                $m->extension = $extension;
            }
            $m->save();
            $response['extraData'] = ['inflate' => pxLang($request->lang,'','common.action_success') ];
            $this->saveTractAction($this->getTrackData(title: "LibSpecialization was created by ".$request?->auth?->name,request: $request));
            DB::commit();
            return $this->response(['type' => 'success', 'data' => $response]);
        } catch (\Exception $e) {
            DB::rollback();
            $this->saveError($this->getSystemError(['name' => 'LibSpecialization_store_error']), $e);
            return $this->response(['type' => 'noUpdate', 'title' => pxLang($request->lang,'','common.server_wrong')]);
        }
    }

    /**
     * Update resource
     *
     * @param Requets $request
     * @param integer|string $categoryId
     * @param integer|string $subcategoryId
     * @param integer|string $id
     * @return JsonResponse
     */
    public function update($request,$categoryId,$subcategoryId,$id) : JsonResponse
    {
        $row = LibSpecialization::where('lib_subcategory_id',$subcategoryId)->find($id);
        if(empty($row)){
            return  $this->response(['type' => 'noUpdate', 'title' =>  '<span class="text-danger">Requestd resource not found, try again </span>']);
        }
        $rowRef = [...$row->toArray()];
        $row->fill([
            'name' => $request->name,
        ]);
        $path = imagePaths()['dyn_image'];
        $image = $request->file('image');
        if ($request->hasFile('image')) {
            $this->deleteImageVersions([
                'path' => $path,
                'image_link' => $row->image,
                'extension' => $row->extension,
                'sizes' =>  $this->sizes
            ]);
            $image_link = (string) Uuid::generate(4);
            $extension = $image->getClientOriginalExtension();
            $this->imageVersioning([
                'image' => $image, 'path' => $path, 'image_link' => $image_link, 'extension' => $extension,
                'appendSize' => true,
                'onlyAppend' => $this->sizes
            ]);
            $row->image = $image_link;
            $row->extension = $extension;
        }
        if($row->isDirty()){
            $validator = Validator::make($request->all(), (new ValidateUpdateLibSpecialization())->rules($request,$row));
            if ($validator->fails()) {
                return $this->response(['type' => 'validation','errors' => $validator->errors()]);
            }
            DB::beginTransaction();
            try {
                $row->save();
                $data['extraData'] = ["inflate" =>  pxLang($request->lang,'','common.action_success')];
                $this->saveTractAction($this->getTrackData(title: " LibSpecialization  ".$row?->name.' was updated by '.$request?->auth?->name,request: $request, row: $rowRef, type: 'to'));
                DB::commit();
                return $this->response(['type' => 'success','data' => $data]);
            } catch (\Exception $e) {
                DB::rollback();
                $this->saveError($this->getSystemError(['name'=>'LibSpecialization_update_error']), $e);
                return $this->response(["type"=>"wrong","lang"=>"server_wrong"]);
            }
        } else {
            return $this->response(['type' => 'noUpdate', 'title' =>  '<span class="text-success">'.pxLang($request->lang,'','common.no_change').'</span>']);
        }
    }

    /**
     *  Bulk update list resource
     *
     * @param Request $request
     * @param integer|string $categoryId
     * @param integer|string $subcategoryId
     * @return JsonResponse
     */
    public function updateList($request,$categoryId,$subcategoryId) : JsonResponse
    {
        $i = LibSpecialization::where('lib_subcategory_id',$subcategoryId)->whereIn('id',$request->ids)->select(['id','name'])->get();;
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
                        "inflate" => pxLang($request->lang,'','common.action_update_success')
                    ];
                    $this->saveTractAction($this->getTrackData(title: "LibSpecialization list was updated by ".$request?->auth?->name, request: $request));
                    DB::commit();
                    return $this->response(['type' => 'success','data' => $data]);
                } catch (\Exception $e) {
                    DB::rollback();
                    $this->saveError($this->getSystemError(['name' => 'LibSpecialization_bulk_update_error']), $e);
                    return $this->response(['type' => 'wrong', 'lang' => 'server_wrong']);
                }
            } else {
                return $this->response(['type' => 'noUpdate', 'title' =>  '<span class="text-success"> '.pxLang($request->lang,'','common.no_change').'  </span>']);
            }

        } else {
            return $this->response(['type' => 'noUpdate', 'title' => pxLang($request->lang,'','common.went_wrong')]);
        }
    }

    /**
     * Bulk delete list resource
     *
     * @param Request $request
     * @param integer|string $categoryId
     * @param integer|string $subcategoryId
     * @return JsonResponse
     */
    public function deleteList($request,$categoryId,$subcategoryId) : JsonResponse
    {
        $errors = [];
        $i = LibSpecialization::where('lib_subcategory_id',$subcategoryId)->whereIn('id',$request->ids)->select(['id','image','extension'])->get();
        if (count($i) > 0) {
            if (count($errors) > 0) {
                return $this->response(['type'=>'bigError','errors'=>$errors]);
            }
            DB::beginTransaction();
            try {
                foreach ($i as $key => $value) {
                    $this->deleteImageVersions([
                        'path' => imagePaths()['dyn_image'],
                        'image_link' => $value->image,
                        'extension' => $value->extension,
                        'sizes' =>  $this->sizes
                    ]);
                    $value->delete();
                }
                $data['extraData'] = [
                    "inflate" => pxLang($request->lang,'','common.action_delete_success'),
                    "redirect" => null
                ];
                $this->saveTractAction($this->getTrackData(title: "LibSpecialization list was deleted by ".$request?->auth?->name, request: $request));
                DB::commit();
                return $this->response(['type' => 'success',"data"=>$data]);
            } catch (\Exception $e) {
                DB::rollback();
                $this->saveError($this->getSystemError(['name' => 'LibSpecialization_store_error']), $e);
                return $this->response(['type' => 'wrong', 'lang' => 'server_wrong']);
            }
        } else {
            return $this->response(['type' => 'noUpdate', 'title' =>  pxLang($request->lang,'','common.no_data_selected')]);
        }
    }

}
