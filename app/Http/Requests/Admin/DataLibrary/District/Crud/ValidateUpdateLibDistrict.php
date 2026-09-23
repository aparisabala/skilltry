<?php

namespace App\Http\Requests\Admin\DataLibrary\District\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
class ValidateUpdateLibDistrict extends FormRequest
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
        $rules =  [];
        if($row->isDirty('name') || $row->isDirty('lib_division_id')) {
            $rules['name'] = [
                'required', 'string', 'min:2', 'max:253',
                \Illuminate\Validation\Rule::unique('lib_districts')->where(fn($q) => $q->where('lib_division_id', request('lib_division_id')))->ignore($row->id),
            ];
            $rules['lib_division_id'] = 'required|exists:lib_divisions,id';
        }
        return $rules;
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
