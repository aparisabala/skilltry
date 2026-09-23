<?php

namespace App\Http\Controllers\Api\V1\Admin\Hrm\User\Crud\Modify\OpdFees\Form\Update;
use App\Http\Controllers\Api\ApiController as Controller;
use App\Models\AdminUser;
use App\Repositories\Admin\Hrm\User\Crud\Modify\OpdFees\Form\Update\IAdminUserOpdFeesOpdFeesUpdateRepository;
use App\Traits\BaseTrait;
use App\Models\AdminUserOpdFees;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
#[\Dedoc\Scramble\Attributes\Group('Human Resource / Doctor OPD Fees')]
class AdminUserOpdFeesOpdFeesUpdateController extends Controller {

    use BaseTrait;
    public function __construct(private IAdminUserOpdFeesOpdFeesUpdateRepository $iAdminUserOpdFeesOpdFeesUpdateRepo) {

        $this->lang= 'admin.hrm.user.crud.modify.opd-fees.form.update';
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read the doctor OPD/hospital/service/IPD fees update form data')]
    #[\Dedoc\Scramble\Attributes\QueryParameter('admin_user_id', type: 'int', required: true, description: 'The doctor (admin_user) this fee setup belongs to')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(Request $request) : JsonResponse
    {
        $data = $this->iAdminUserOpdFeesOpdFeesUpdateRepo->index($request);
        $data['doctor'] = AdminUser::with(['role','opdFees'])->find($request->admin_user_id);
        $data['lang'] = $this->lang;
        if(!$data['doctor'] || $data['doctor']?->role?->code != 'DC') {
            return response()->json(['success' => false, 'message' => 'Doctor not found'], 404);
        }
        if($data['doctor']?->opdFees == null) {
            AdminUserOpdFees::insert(['admin_user_id' => $data['doctor']?->id]);
            $data['doctor'] = AdminUser::with(['role','opdFees'])->find($request->admin_user_id);
        }
        $data['admin_user_id'] = $request->admin_user_id;
        return response()->json(['success' => true, 'data' => $data]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Update the doctor OPD/hospital/service/IPD fees')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('id', type: 'int', required: true, description: 'The admin_user_opd_fees row id')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('doctor_fees', type: 'number', required: true)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('hospital_fees', type: 'number', required: true)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('service_fees', type: 'number', required: true)]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ipd_fees', type: 'number', required: false, description: 'Charged to the IPD bill when this doctor is called as a consultant. Leave 0 to use doctor_fees')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function update(Request $request) : JsonResponse
    {
       return $this->iAdminUserOpdFeesOpdFeesUpdateRepo->update($request);
    }
}
