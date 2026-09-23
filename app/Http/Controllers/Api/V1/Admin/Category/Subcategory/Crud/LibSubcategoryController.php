<?php

namespace App\Http\Controllers\Api\V1\Admin\Category\Subcategory\Crud;
use App\Http\Controllers\Api\ApiController as Controller;
use App\Http\Requests\Admin\Category\Subcategory\Crud\ValidateStoreLibSubcategory;
use App\Repositories\Admin\Category\Subcategory\Crud\ILibSubcategoryRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
#[\Dedoc\Scramble\Attributes\Group('Category / Subcategory')]
class LibSubcategoryController  extends Controller {

    use BaseTrait;
    public function __construct(private ILibSubcategoryRepository $iLibSubcategoryRepo) {

        $this->lang= 'admin.category.subcategory';
    }


    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read create-form data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function create($category,Request $request): JsonResponse
    {
        return $this->index($category,$request);
    }

    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read Subcategory data (scoped to a Category)')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function index($category,Request $request) : JsonResponse
    {
        $data = $this->iLibSubcategoryRepo->index($request,$category);
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
    public function list(Request $request,$category) : JsonResponse
    {
        return  $this->iLibSubcategoryRepo->list($request,$category);
    }


    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Create record')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('name', type: 'string', required: true, description: 'Unique name within the parent Category, 2-253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('image', type: 'string', required: false, description: 'Optional icon image (jpg,png,jpeg,webp, max 2MB)')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function store($category,ValidateStoreLibSubcategory $request): JsonResponse
    {
        return $this->iLibSubcategoryRepo->store($request,$category);
    }


    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Read edit-form data')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function edit($category,$subcategory,Request $request) : JsonResponse
    {
        $data = $this->iLibSubcategoryRepo->index($request,$category,$subcategory);
        $data['lang'] = $this->lang;
        return response()->json(['success' => true, 'data' => $data]);
    }


    #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Update record')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('name', type: 'string', required: true, description: 'Unique name within the parent Category, 2-253 characters')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('image', type: 'string', required: false, description: 'Optional icon image (jpg,png,jpeg,webp, max 2MB)')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function update(Request $request, $category, $subcategory) : JsonResponse
    {
        return $this->iLibSubcategoryRepo->update($request,$category,$subcategory);
    }


     #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Delete selected records')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function deleteList(Request $request,$category) : JsonResponse
    {
       return $this->iLibSubcategoryRepo->deleteList($request,$category);
    }


     #[\Dedoc\Scramble\Attributes\Endpoint(title: 'Bulk update selected records')]
    #[\Dedoc\Scramble\Attributes\BodyParameter('ids', type: 'int[]', required: true, description: 'Selected record IDs')]
    #[\Dedoc\Scramble\Attributes\Response(200, description: 'JSON response. For legacy mutations also check success and errors.', type: 'array{success: bool, data?: mixed, errors?: array<string, array<string>>, noUpdate?: bool, title?: string, message?: string}')]
    #[\Dedoc\Scramble\Attributes\Response(401, description: 'Missing, invalid or expired token; or incorrect login credentials', type: 'array{success: bool, message: string}')]
    #[\Dedoc\Scramble\Attributes\Response(403, description: 'Disabled account, incomplete setup or missing permission', type: 'array{success: bool, message: string, code?: string, ability?: string}')]
    public function updateList(Request $request,$category) : JsonResponse
    {
       return $this->iLibSubcategoryRepo->updateList($request,$category);
    }

}
