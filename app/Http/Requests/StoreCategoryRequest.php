<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|exists:categories,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.exists' => 'The selected parent category is invalid.',
            'logo.image' => 'The logo must be an image.',
            'logo.mimes' => 'The logo must be a file of type: jpeg, png, jpg.',
            'logo.max' => 'The logo may not be greater than 2MB.',
            'is_active.required' => 'The status field is required.',
            'is_active.boolean' => 'The status must be either active or inactive.',
            'translations.required' => 'At least one translation is required.',
            'translations.*.language_id.required' => 'The language ID is required for each translation.',
            'translations.*.language_id.exists' => 'The selected language is invalid.',
            'translations.*.name.required' => 'The category name is required for each translation.',
            'translations.*.name.max' => 'The category name may not be greater than 255 characters.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->is('api/*')) {
            throw new ValidationException($validator, response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422));
        }

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}