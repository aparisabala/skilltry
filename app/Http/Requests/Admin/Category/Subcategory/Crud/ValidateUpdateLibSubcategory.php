<?php

namespace App\Http\Requests\Admin\Category\Subcategory\Crud;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
class ValidateUpdateLibSubcategory extends FormRequest
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
        $categoryId = $request->route('category');
        if($row->isDirty('name')) {
            $rules['name'] = [
                'required', 'string', 'min:2', 'max:253',
                Rule::unique('lib_subcategories')->where(fn($q) => $q->where('lib_category_id', $categoryId))->ignore($row->id),
            ];
        }
        if($request->hasFile('image')) {
            $rules['image'] = 'nullable|file|mimes:jpg,png,jpeg,webp|max:2024';
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
