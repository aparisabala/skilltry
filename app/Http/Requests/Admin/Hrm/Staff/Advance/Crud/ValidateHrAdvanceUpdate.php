<?php

namespace App\Http\Requests\Admin\Hrm\Staff\Advance\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ValidateHrAdvanceUpdate extends FormRequest
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
    public function rules($request, $row): array
    {
        return [
            'amount' => 'required|numeric|min:0|max:999999999',
            'advance_date' => 'required|date',
            'monthly_deduction' => 'required|numeric|min:0|max:999999999',
            'reason' => 'nullable|string|max:253',
            'status' => 'required|string|max:253|in:Pending,Approved,Rejected,Settled',
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
