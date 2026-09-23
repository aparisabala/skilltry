<?php

namespace App\Http\Requests\Api\V1\Admin\Account;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * A transaction for the books, posted directly (no draft step): receive / pay (cash or bank income / expense) or
 * deposit / withdraw (a transfer between a cash and a bank ledger).
 */
class PostTransactionRequest extends FormRequest
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
            'kind' => 'required|in:receive,pay,deposit,withdraw',
            'method' => 'required_if:kind,receive,pay|nullable|in:cash,bank',
            'ledger_id' => 'required|integer|exists:ac_ledgers,id',
            'counter_ledger_id' => 'required|integer|exists:ac_ledgers,id',
            'tran_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|gt:0',
            'items.*.folio_number' => 'nullable|string|max:100',
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
