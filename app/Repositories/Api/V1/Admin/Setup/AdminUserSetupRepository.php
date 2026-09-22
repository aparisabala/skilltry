<?php

namespace App\Repositories\Api\V1\Admin\Setup;

use App\Repositories\BaseRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;
use App\Http\Requests\Admin\Setup\ValidateAdminUserProfileSetup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Models\AdminUser;

class AdminUserSetupRepository extends BaseRepository implements IAdminUserSetupRepository
{
    use BaseTrait;
    public function __construct()
    {

       $this->sizes =  [
            ['width'=>300, 'height'=> 300,'com'=> 90],
            ['width'=>80, 'height'=> 80,'com'=> 100],
       ];
       $this->lang = 'admin.profile.setup';
    }

    /**
     * View AdminUser profile setup
     *
     * @param Request $request
     * @return View
    */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        if($user->setup_done == "yes") {
            return response()->json(['success' => false, 'message' => 'The requested resource or account state is unavailable.', 'redirect' => route('admin.dashboard.index')], 409);
        }
        $data['item'] = $user;
        $data['lang'] = $this->lang;
        return response()->json(['success' => true, 'data' => $data]);
    }

     /**
     * AdminUser Profile Update
     *
     * @param  Request $request
     * @return JsonResponse
    */
    public function update(Request $request): JsonResponse
    {
        $user = AdminUser::find($request?->auth?->id);
        if (empty($user)) {
            return $this->response([ 'type' => "noUpdate", "title" => pxLang($this->lang,'mgs.no_user')]);
        }
        $validator = Validator::make($request->all(), (new ValidateAdminUserProfileSetup())->rules($request, $user));
        if ($validator->fails()) {
            return $this->response(['type' => 'validation', 'errors' => $validator->errors()]);
        }
        try {
            $user->name = $request->name;
            $user->mobile_number = $request->mobile_number;
            $user->email = $request->email;
            $user->password = Hash::make($request->confim_password);
            $user->setup_done = "yes";
            $path = imagePaths()['dyn_image'];
            $image = $request->file('image');
            if (!empty($image)) {
                $this->deleteImageVersions([
                    'path' => $path,
                    'image_link' => $user->image,
                    'extension' => $user->extension,
                    'sizes' =>  $this->sizes
                ]);
                $image_link = (string) Uuid::generate(4);
                $extension = $image->getClientOriginalExtension();
                $this->imageVersioning([
                    'image' => $image, 'path' => $path, 'image_link' => $image_link, 'extension' => $extension,
                    'appendSize' => true,
                    'onlyAppend' => $this->sizes
                ]);
                $user->image = $image_link;
                $user->extension = $extension;
            }
            $user->save();
            $data['extraData'] = [
                "redirect" => 'admin/dashboard',
                "inflate" =>  pxLang($this->lang,'mgs.update_success')
            ];
            return $this->response(['type' => 'success', "data" => $data]);
        } catch (\Exception $e) {
            $this->saveError($this->getSystemError(['name' => 'update_AdminUser_profile_error']), $e);
            return $this->response(["type" => "wrong", "lang" => "server_wrong"]);
        }
    }
}
