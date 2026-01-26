<?php


namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setup\ValidateAdminUserInfoUpdate;
use App\Http\Requests\Admin\Setup\ValidateAdminUserPasswordUpdate;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Contracts\View\View;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Models\AdminUser;
use App\Http\Requests\AdminUser\Setup\ValidateAdminUserProfileUpdate;
use Hash;
class AdminUserProfileUpdateController extends Controller
{
    use BaseTrait;
    public function __construct()
    {
        $this->middleware(['auth:admin','HasAdminUserPassword','HasAdminUserAuth']);
        $this->sizes =  [
            ['width'=>300, 'height'=> 300,'com'=> 90],
            ['width'=>80, 'height'=> 80,'com'=> 100],
        ];
    }

    /**
     * View AdminUser profile setup
     *
     * @param Request $request
     * @return View
    */
    public function index(Request $request)
    {
        $user = Auth::user();
        $data['item'] = $user;
        $data['lang'] = 'admin.user.update';
        return  view('admin.pages.setup.user.index')->with("data",$data);
    }

    /**
     * View AdminUser profile setup
     *
     * @param Request $request
     * @return View
    */
    public function password(Request $request)
    {
        $user = Auth::user();
        $data['item'] = $user;
        $data['lang'] = 'admin.user.pass.update';
        return  view('admin.pages.setup.pass.index')->with("data",$data);
    }




     /**
     * AdminUser Profile Update
     *
     * @param  Request $request
     * @return JsonResponse
    */
    public function updateProfile(Request $request)
    {
        $lang = 'admin.user.update';
        $user = AdminUser::find($request?->auth?->id);
        if (empty($user)) {
            return $this->response([ 'type' => "noUpdate", "title" => pxLang($lang,'mgs.no_user')]);
        }
        $validator = Validator::make($request->all(), (new ValidateAdminUserInfoUpdate())->rules($request, $user));
        if ($validator->fails()) {
            return $this->response(['type' => 'validation', 'errors' => $validator->errors()]);
        }
        try {
            $user->name = $request->name;
            $user->mobile_number = $request->mobile_number;
            $user->email = $request->email;
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
                "redirect" => null,
                "inflate" => pxLang($lang,'mgs.update_success')
            ];
            return $this->response(['type' => 'success', "data" => $data]);
        } catch (\Exception $e) {
            $this->saveError($this->getSystemError(['name' => 'update_AdminUser_profile_error']), $e);
            return $this->response(["type" => "wrong", "lang" => "server_wrong"]);
        }
    }

    public function updatePassword(Request $request)
    {
        $data['lang'] = 'admin.user.pass.update';
        $user = AdminUser::find($request?->auth?->id);
        if (empty($user)) {
            return $this->response([ 'type' => "noUpdate", "title" => pxLang($data['lang'] ,'mgs.no_user')]);
        }
        $validator = Validator::make($request->all(), (new ValidateAdminUserPasswordUpdate())->rules($user));
        if ($validator->fails()) {
            return $this->response(['type' => 'validation', 'errors' => $validator->errors()]);
        }
        $user->password = Hash::make($request->confirm_password);
        $user->save();
        $reposne['extraData'] = [
            "inflate" =>  pxLang($data['lang'],'mgs.update_success'),
            "redirect" => 'admin/logout'
        ];
        return $this->response(['type' => 'success', "data" => $reposne]);
    }
}
