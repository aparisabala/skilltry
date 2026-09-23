<?php

namespace App\Http\Requests\Api\V1\Admin\Account;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * Change an account ledger (its type stays), mobile API.
 */
class UpdateLedgerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function message(): array
    {
        return [];
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:253',
            'note' => 'sometimes|nullable|string|max:253',
            'opening_balance' => 'sometimes|nullable|numeric|min:0',
            'status' => 'sometimes|required|in:Active,Off',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ]);
        throw (new ValidationException($validator, $response))->errorBag($this->errorBag);
    }
}
