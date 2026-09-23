<?php

namespace App\Http\Requests\Api\V1\Admin\Account;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * An account ledger, mobile API.
 */
class StoreLedgerRequest extends FormRequest
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
            'name' => 'required|string|max:253|unique:ac_ledgers,name',
            'ledger_type' => 'required|in:asset,cash,bank,income,expense',
            'note' => 'nullable|string|max:253',
            'opening_balance' => 'nullable|numeric|min:0',
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
