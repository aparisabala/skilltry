<?php

namespace App\Http\Requests\Admin\Hrm\Setup\PayComponent\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
class ValidateStoreHrPayComponent extends FormRequest
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
    public function rules(): array
    {
        return [
'name' => 'required|string|min:2|max:253|unique:hr_pay_components,name',
'component_type' => 'required|string|in:Earning,Deduction',
'calc_type' => 'required|string|in:Fixed,Percent',
'default_value' => 'nullable|numeric',
        ];
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
