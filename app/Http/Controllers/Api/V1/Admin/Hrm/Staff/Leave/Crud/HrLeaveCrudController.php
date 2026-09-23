<?php

namespace App\Http\Controllers\Api\V1\Admin\Hrm\Staff\Leave\Crud;
use App\Http\Controllers\Api\ApiController as Controller;
use App\Http\Requests\Admin\Hrm\Staff\Leave\Crud\ValidateHrLeaveStore;
use App\Repositories\Admin\Hrm\Staff\Leave\Crud\IHrLeaveCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
#[\Dedoc\Scramble\Attributes\Group('Human Resource / Leave')]
class HrLeaveCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IHrLeaveCrudRepository $iHrLeaveCrudRepo) {

        $this->lang= 'admin.hrm.staff.leave.crud';
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read create-form data')]
    #[\Dedoc\Scramble\Attributes\QueryParameter('admin_user_id', type: 'int', required: true, description: 'The employee (AdminUser) this leave list is scoped to')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function create(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read leave data')]
    #[\Dedoc\Scramble\Attributes\QueryParameter('admin_user_id', type: 'int', required: true, description: 'The employee (AdminUser) this leave list is scoped to')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(Request $request) : JsonResponse
    {
        $data = $this->iHrLeaveCrudRepo->index($request);
        $data['lang'] = $this->lang;
        $data['leaveTypes'] = class_exists(\App\Models\HrLeaveType::class)
            ? \App\Models\HrLeaveType::where('status','Active')->orderBy('name')->get(['id','name'])
            : collect();
        return response()->json(['success' => true, 'data' => $data]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'List records (DataTables)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('admin_user_id', type: 'int', required: true, description: 'The employee (AdminUser) to filter by')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('draw', type: 'int', required: false, description: 'DataTables draw counter')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('start', type: 'int', required: false, description: 'Zero-based row offset')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('length', type: 'int', required: false, description: 'Number of rows')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('search', type: 'array{value: string}', required: false, description: 'DataTables search value')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{draw: int, recordsTotal: int, recordsFiltered: int, data: array<array<string, mixed>>}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function list(Request $request) : JsonResponse
    {
        return  $this->iHrLeaveCrudRepo->list($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Create record')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('admin_user_id', type: 'int', required: true, description: 'The employee (AdminUser) requesting the leave')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('hr_leave_type_id', type: 'int', required: true, description: 'Must reference an existing hr_leave_types row')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('from_date', type: 'string', required: true, description: 'Leave start date, Y-m-d')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('to_date', type: 'string', required: true, description: 'Leave end date, Y-m-d, must not be before from_date')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('reason', type: 'string', required: false, description: 'Free text reason, up to 253 characters')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function store(ValidateHrLeaveStore $request): JsonResponse
    {
        return $this->iHrLeaveCrudRepo->store($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read edit-form data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function edit($id,Request $request) : JsonResponse
    {
        $data = $this->iHrLeaveCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        $data['leaveTypes'] = class_exists(\App\Models\HrLeaveType::class)
            ? \App\Models\HrLeaveType::where('status','Active')->orderBy('name')->get(['id','name'])
            : collect();
        return response()->json(['success' => true, 'data' => $data]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Update record')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('hr_leave_type_id', type: 'int', required: true, description: 'Must reference an existing hr_leave_types row')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('from_date', type: 'string', required: true, description: 'Leave start date, Y-m-d')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('to_date', type: 'string', required: true, description: 'Leave end date, Y-m-d, must not be before from_date')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('reason', type: 'string', required: false, description: 'Free text reason, up to 253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('status', type: 'string', required: true, description: 'One of Pending, Approved, Rejected')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iHrLeaveCrudRepo->update($request,$id);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Delete selected records')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function deleteList(Request $request) : JsonResponse
    {
       return $this->iHrLeaveCrudRepo->deleteList($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Bulk update selected records')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('serial', type: 'object', required: true, description: 'Map of record id => new serial value')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function updateList(Request $request) : JsonResponse
    {
       return $this->iHrLeaveCrudRepo->updateList($request);
    }

}
