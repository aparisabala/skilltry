<?php

namespace App\Http\Requests\Admin\Hrm\User\Crud\Modify\Designation\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
class ValidateStoreAdminUserDesignation extends FormRequest
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
        'name' => [
            'required',
            'string',
            'max:253',
             Rule::unique('admin_user_designations')
                ->where(function ($query) use ($request) {
                        return $query->where('admin_user_id', $request->admin_user_id);
                    }),
            ],
            'passing_year' => 'nullable|string|max:253',
            'description' => 'nullable|string'
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
