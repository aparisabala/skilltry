<?php

namespace App\Http\Requests\Admin\Hrm\Staff\PayComponent\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
            'name' => ['required', 'string', 'max:253', Rule::unique('hr_pay_components', 'name')],
            'component_type' => 'required|string|max:253|in:Earning,Deduction',
            'calc_type' => 'required|string|max:253|in:Fixed,Percent',
            'default_value' => 'nullable|numeric|min:0|max:999999999',
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
