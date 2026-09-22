<?php

namespace App\Http\Controllers\Api\V1\Admin\Hrm\User\Policy\Modal\UpdatePolicyItem;
use App\Http\Controllers\Api\ApiController as Controller;
use App\Traits\BaseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\Admin\Hrm\User\Policy\Modal\UpdatePolicyItem\IUpdatePolicyItemRepository;
//vpx_imports

#[\Dedoc\Scramble\Attributes\Group('HR permissions')]
class UpdatePolicyItemModalController extends Controller {

    use BaseTrait;
    public function __construct(private IUpdatePolicyItemRepository $iUpdatePolicyItemRepo) {

        $this->lang= 'admin.hrm.user.policy.modal.update-policy-item';
        $this->middleware(function ($request, $next) {
            $request->merge(['lang' => $this->lang]);
            return $next($request);
        });

    }

    
    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read permission editor data')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('policy_name', type: 'string', required: false, description: 'Optional policy section name')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function display(Request $request) : JsonResponse
    {
        $data['lang'] = $this->lang;
        $data = [...$data,...$this->iUpdatePolicyItemRepo->display($request)];
        return response()->json(['success' => true, 'data' => $data]);
    }
    //vpx_attach

}
