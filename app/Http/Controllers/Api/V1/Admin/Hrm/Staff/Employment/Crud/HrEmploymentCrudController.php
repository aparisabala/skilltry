<?php

namespace App\Http\Controllers\Api\V1\Admin\Hrm\Staff\Employment\Crud;
use App\Http\Controllers\Api\ApiController as Controller;
use App\Http\Requests\Admin\Hrm\Staff\Employment\Crud\ValidateHrEmploymentStore;
use App\Models\AdminUser;
use App\Repositories\Admin\Hrm\Staff\Employment\Crud\IHrEmploymentCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
#[\Dedoc\Scramble\Attributes\Group('Human Resource / Employment History')]
class HrEmploymentCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IHrEmploymentCrudRepository $iHrEmploymentCrudRepo) {

        $this->lang= 'admin.hrm.staff.employment.crud';
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read create-form data')]
    #[\Dedoc\Scramble\Attributes\QueryParameter('admin_user_id', type: 'int', required: true, description: 'The employee (admin_users.id) this employment history list is scoped to')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function create(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    /**
     * Employment history of an employee: joined, confirmed, promotion, transfer, increment, resigned, etc.
     */
    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read employee employment history list data')]
    #[\Dedoc\Scramble\Attributes\QueryParameter('admin_user_id', type: 'int', required: true, description: 'The employee (admin_users.id) this employment history list is scoped to')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(Request $request) : JsonResponse
    {
        $data = $this->iHrEmploymentCrudRepo->index($request);
        $data['lang'] = $this->lang;
        $data['employee'] = AdminUser::with(['role'])->find($request->admin_user_id);
        $data['admin_user_id'] = $request->admin_user_id;
        return response()->json(['success' => true, 'data' => $data]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'List records (DataTables)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('admin_user_id', type: 'int', required: true, description: 'The employee (admin_users.id) to filter the list by')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('draw', type: 'int', required: false, description: 'DataTables draw counter')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('start', type: 'int', required: false, description: 'Zero-based row offset')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('length', type: 'int', required: false, description: 'Number of rows')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('search', type: 'array{value: string}', required: false, description: 'DataTables search value')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{draw: int, recordsTotal: int, recordsFiltered: int, data: array<array<string, mixed>>}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function list(Request $request) : JsonResponse
    {
        return  $this->iHrEmploymentCrudRepo->list($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Create record')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('admin_user_id', type: 'int', required: true, description: 'The employee (admin_users.id) this employment entry belongs to')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('change_type', type: 'string', required: true, description: 'One of Joined,Confirmed,Promotion,Transfer,Increment,Demotion,Suspended,Resigned,Terminated,Retired', default: 'Joined')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('effective_date', type: 'string', required: true, description: 'Effective date (date)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('lib_department_id', type: 'int', required: false, description: 'Department (lib_departments.id)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('designation_title', type: 'string', required: false, description: 'Designation title, max 253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('salary', type: 'number', required: false, description: 'Salary, 0-999999999')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('note', type: 'string', required: false, description: 'Free-text note')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function store(ValidateHrEmploymentStore $request): JsonResponse
    {
        return $this->iHrEmploymentCrudRepo->store($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read edit-form data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function edit($id,Request $request) : JsonResponse
    {
        $data = $this->iHrEmploymentCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        $data['employee'] = AdminUser::with(['role'])->find($data['item']?->admin_user_id);
        $data['admin_user_id'] = $data['item']?->admin_user_id;
        return response()->json(['success' => true, 'data' => $data]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Update record')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('change_type', type: 'string', required: true, description: 'One of Joined,Confirmed,Promotion,Transfer,Increment,Demotion,Suspended,Resigned,Terminated,Retired')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('effective_date', type: 'string', required: true, description: 'Effective date (date)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('lib_department_id', type: 'int', required: false, description: 'Department (lib_departments.id)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('designation_title', type: 'string', required: false, description: 'Designation title, max 253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('salary', type: 'number', required: false, description: 'Salary, 0-999999999')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('note', type: 'string', required: false, description: 'Free-text note')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iHrEmploymentCrudRepo->update($request,$id);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Delete selected records')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function deleteList(Request $request) : JsonResponse
    {
       return $this->iHrEmploymentCrudRepo->deleteList($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Bulk update selected records')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('serial', type: 'array<int,int>', required: true, description: 'Map of record id => new serial value')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function updateList(Request $request) : JsonResponse
    {
       return $this->iHrEmploymentCrudRepo->updateList($request);
    }

}
