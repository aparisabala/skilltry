<?php

namespace App\Http\Controllers\Api\V1\Admin\Hrm\Setup\PayComponent\Crud;
use App\Http\Controllers\Api\ApiController as Controller;
use App\Http\Requests\Admin\Hrm\Setup\PayComponent\Crud\ValidateStoreHrPayComponent;
use App\Repositories\Admin\Hrm\Setup\PayComponent\Crud\IHrPayComponentCrudRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
#[\Dedoc\Scramble\Attributes\Group('HR / Setup / Pay Component')]
class HrPayComponentCrudController  extends Controller {

    use BaseTrait;
    public function __construct(private IHrPayComponentCrudRepository $iHrPayComponentCrudRepo) {

        $this->lang= 'admin.hrm.setup.paycomponent';
    }


    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read create-form data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function create(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read PayComponent data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index(Request $request) : JsonResponse
    {
        $data = $this->iHrPayComponentCrudRepo->index($request);
        $data['lang'] = $this->lang;
        return response()->json(['success' => true, 'data' => $data]);
    }


    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'List records (DataTables)')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('draw', type: 'int', required: false, description: 'DataTables draw counter')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('start', type: 'int', required: false, description: 'Zero-based row offset')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('length', type: 'int', required: false, description: 'Number of rows')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('search', type: 'array{value: string}', required: false, description: 'DataTables search value')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{draw: int, recordsTotal: int, recordsFiltered: int, data: array<array<string, mixed>>}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function list(Request $request) : JsonResponse
    {
        return  $this->iHrPayComponentCrudRepo->list($request);
    }


    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Create record')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('name', type: 'string', required: true, description: 'Unique name, max 253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('component_type', type: 'string', required: true, description: 'Earning or Deduction')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('calc_type', type: 'string', required: true, description: 'Fixed or Percent')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('default_value', type: 'number', required: false, description: 'Default numeric value')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function store(ValidateStoreHrPayComponent $request): JsonResponse
    {
        return $this->iHrPayComponentCrudRepo->store($request);
    }


    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read edit-form data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function edit($id,Request $request) : JsonResponse
    {
        $data = $this->iHrPayComponentCrudRepo->index($request,$id);
        $data['lang'] = $this->lang;
        return response()->json(['success' => true, 'data' => $data]);
    }


    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Update record')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('name', type: 'string', required: true, description: 'Unique name, max 253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('component_type', type: 'string', required: true, description: 'Earning or Deduction')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('calc_type', type: 'string', required: true, description: 'Fixed or Percent')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('default_value', type: 'number', required: false, description: 'Default numeric value')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('status', type: 'string', required: false, description: 'Active or Inactive')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function update(Request $request, $id) : JsonResponse
    {
        return $this->iHrPayComponentCrudRepo->update($request,$id);
    }


     #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Delete selected records')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function deleteList(Request $request) : JsonResponse
    {
       return $this->iHrPayComponentCrudRepo->deleteList($request);
    }


     #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Bulk update selected records')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function updateList(Request $request) : JsonResponse
    {
       return $this->iHrPayComponentCrudRepo->updateList($request);
    }

}
