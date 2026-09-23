<?php

namespace App\Http\Requests\Admin\Hrm\Staff\PayGrade\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
class ValidateUpdateHrPayGrade extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:253', Rule::unique('hr_pay_grades', 'name')->ignore($row->id)],
            'min_basic' => 'required|numeric|min:0|max:999999999',
            'max_basic' => 'required|numeric|min:0|max:999999999',
            'description' => 'nullable|string',
            'status' => 'nullable|in:Active,Inactive',
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
