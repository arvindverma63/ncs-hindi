<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateGeneralSettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'app_name' => 'required|string|max:255',
            'app_phone' => 'required|string|max:20',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'login_page_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'app_name.required' => 'The app name is required.',
            'app_name.max' => 'The app name cannot exceed 255 characters.',
            'app_phone.required' => 'The app phone is required.',
            'app_phone.max' => 'The app phone cannot exceed 20 characters.',
            'logo.image' => 'The logo must be an image.',
            'logo.mimes' => 'The logo must be a file of type: jpeg, png, jpg, gif.',
            'logo.max' => 'The logo may not be greater than 2MB.',
            'favicon.image' => 'The favicon must be an image.',
            'favicon.mimes' => 'The favicon must be a file of type: jpeg, png, jpg, gif.',
            'favicon.max' => 'The favicon may not be greater than 2MB.',
            'login_page_logo.image' => 'The login page logo must be an image.',
            'login_page_logo.mimes' => 'The login page logo must be a file of type: jpeg, png, jpg, gif.',
            'login_page_logo.max' => 'The login page logo may not be greater than 2MB.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->is('api/*')) {
            throw new ValidationException($validator, response()->json([
                'status' => false, 
                'message' => $validator->errors()
            ], 201));
        }

        throw (new ValidationException($validator))
                    ->errorBag($this->errorBag)
                    ->redirectTo($this->getRedirectUrl());
    }
}