<?php

namespace App\Http\Requests\Admin\Hrm\Staff\Employment\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ValidateHrEmploymentStore extends FormRequest
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
    public function rules(Request $request): array
    {
        return [
            'admin_user_id' => 'required|integer|exists:admin_users,id',
            'change_type' => 'required|string|max:253|in:Joined,Confirmed,Promotion,Transfer,Increment,Demotion,Suspended,Resigned,Terminated,Retired',
            'effective_date' => 'required|date',
            'lib_department_id' => 'nullable|integer|exists:lib_departments,id',
            'designation_title' => 'nullable|string|max:253',
            'salary' => 'nullable|numeric|min:0|max:999999999',
            'note' => 'nullable|string',
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
