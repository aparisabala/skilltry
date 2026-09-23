<?php

namespace App\Http\Requests\Admin\Hrm\Setup\PayComponent\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
class ValidateUpdateHrPayComponent extends FormRequest
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
        $rules =  [];
        if($row->isDirty('name')) {
            $rules['name'] = 'required|string|min:2|max:253|unique:hr_pay_components,name,'.$row->id;
        }
        if($row->isDirty('component_type')) {
            $rules['component_type'] = 'required|string|in:Earning,Deduction';
        }
        if($row->isDirty('calc_type')) {
            $rules['calc_type'] = 'required|string|in:Fixed,Percent';
        }
        if($row->isDirty('default_value')) {
            $rules['default_value'] = 'nullable|numeric';
        }
        if($row->isDirty('status')) {
            $rules['status'] = 'required|string|in:Active,Inactive';
        }
        if($row->isDirty('serial')) {
            $rules['serial'] = 'nullable|integer';
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
