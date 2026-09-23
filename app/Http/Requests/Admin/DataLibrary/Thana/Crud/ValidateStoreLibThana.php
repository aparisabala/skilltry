<?php

namespace App\Http\Requests\Admin\DataLibrary\Thana\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
class ValidateStoreLibThana extends FormRequest
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
            'name' => [
                'required', 'string', 'min:2', 'max:253',
                Rule::unique('lib_thanas')->where(fn($q) => $q->where('lib_district_id', request('lib_district_id'))),
            ],
            'lib_division_id' => 'required|exists:lib_divisions,id',
            'lib_district_id' => 'required|exists:lib_districts,id',
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
