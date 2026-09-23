<?php

namespace App\Http\Requests\Admin\Hrm\Staff\Deduction\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ValidateHrDeductionStore extends FormRequest
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
            'deduction_type' => 'required|string|max:253|in:Penalty,Loan / EMI,Tax,Uniform,Damage / Loss,Other',
            'amount' => 'required|numeric|min:0|max:999999999',
            'deduction_month' => 'required|date',
            'reason' => 'nullable|string|max:253',
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
