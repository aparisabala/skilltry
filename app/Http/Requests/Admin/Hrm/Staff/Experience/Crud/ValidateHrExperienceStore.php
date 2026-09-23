<?php

namespace App\Http\Requests\Admin\Hrm\Staff\Experience\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ValidateHrExperienceStore extends FormRequest
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
            'organization' => 'required|string|max:253',
            'position' => 'required|string|max:253',
            'from_date' => 'required|date',
            'to_date' => 'nullable|date',
            'last_salary' => 'nullable|numeric|min:0|max:999999999',
            'leaving_reason' => 'nullable|string|max:253',
            'responsibilities' => 'nullable|string',
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
