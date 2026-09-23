<?php

namespace App\Http\Controllers\Api\V1\Admin\Hrm\Staff\Experience\Crud;
use App\Http\Controllers\Api\ApiController as Controller;
use App\Http\Requests\Admin\Hrm\Staff\Experience\Crud\ValidateHrExperienceStore;
use App\Models\AdminUser;
use App\Repositories\Admin\Hrm\Staff\Experience\Crud\IHrExperienceCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
#[\Dedoc\Scramble\Attributes\Group('Human Resource / Job Experience')]
class HrExperienceCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IHrExperienceCrudRepository $iHrExperienceCrudRepo) {

        $this->lang= 'admin.hrm.staff.experience.crud';
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read create-form data')]
    #[\Dedoc\Scramble\Attributes\QueryParameter('admin_user_id', type: 'int', required: true, description: 'The employee (admin_users.id) this experience record list is scoped to')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function create(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    /**
     * Job experience of an employee before joining (organization, position, period, last salary).
     */
    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read employee experience list data')]
    #[\Dedoc\Scramble\Attributes\QueryParameter('admin_user_id', type: 'int', required: true, description: 'The employee (admin_users.id) this experience record list is scoped to')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(Request $request) : JsonResponse
    {
        $data = $this->iHrExperienceCrudRepo->index($request);
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
        return  $this->iHrExperienceCrudRepo->list($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Create record')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('admin_user_id', type: 'int', required: true, description: 'The employee (admin_users.id) this experience entry belongs to')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('organization', type: 'string', required: true, description: 'Organization name, max 253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('position', type: 'string', required: true, description: 'Position held, max 253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('from_date', type: 'string', required: true, description: 'Start date (date)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('to_date', type: 'string', required: false, description: 'End date (date)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('last_salary', type: 'number', required: false, description: 'Last drawn salary, 0-999999999')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('leaving_reason', type: 'string', required: false, description: 'Reason for leaving, max 253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('responsibilities', type: 'string', required: false, description: 'Free-text responsibilities')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function store(ValidateHrExperienceStore $request): JsonResponse
    {
        return $this->iHrExperienceCrudRepo->store($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read edit-form data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function edit($id,Request $request) : JsonResponse
    {
        $data = $this->iHrExperienceCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        $data['employee'] = AdminUser::with(['role'])->find($data['item']?->admin_user_id);
        $data['admin_user_id'] = $data['item']?->admin_user_id;
        return response()->json(['success' => true, 'data' => $data]);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Update record')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('organization', type: 'string', required: true, description: 'Organization name, max 253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('position', type: 'string', required: true, description: 'Position held, max 253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('from_date', type: 'string', required: true, description: 'Start date (date)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('to_date', type: 'string', required: false, description: 'End date (date)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('last_salary', type: 'number', required: false, description: 'Last drawn salary, 0-999999999')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('leaving_reason', type: 'string', required: false, description: 'Reason for leaving, max 253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('responsibilities', type: 'string', required: false, description: 'Free-text responsibilities')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iHrExperienceCrudRepo->update($request,$id);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Delete selected records')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function deleteList(Request $request) : JsonResponse
    {
       return $this->iHrExperienceCrudRepo->deleteList($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Bulk update selected records')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('serial', type: 'array<int,int>', required: true, description: 'Map of record id => new serial value')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function updateList(Request $request) : JsonResponse
    {
       return $this->iHrExperienceCrudRepo->updateList($request);
    }

}
