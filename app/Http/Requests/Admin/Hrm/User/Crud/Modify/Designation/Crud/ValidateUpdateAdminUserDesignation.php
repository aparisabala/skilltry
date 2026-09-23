<?php

namespace App\Http\Requests\Admin\Hrm\User\Crud\Modify\Designation\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
class ValidateUpdateAdminUserDesignation extends FormRequest
{
   /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function message() : array
    {
        return [
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules($request,$row): array
    {
        $rules =  [
            'passing_year' => 'nullable|string|max:253',
            'description' => 'nullable|string'
        ];
        if($row->isDirty('name')) {
            $rules['name'] = [
            'required',
            'string',
            'max:253',
             Rule::unique('admin_user_designations')
                ->where(function ($query) use ($request) {
                        return $query->where('admin_user_id', $request->admin_user_id);
                }),
            ];
        }
        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'errors'  => $validator->errors(),
        ]);
        throw (new ValidationException($validator, $response))->errorBag($this->errorBag);
    }
}
