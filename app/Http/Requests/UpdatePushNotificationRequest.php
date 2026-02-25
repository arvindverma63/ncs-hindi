<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdatePushNotificationRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'firebase_service_account' => 'required|file|mimes:json|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'firebase_service_account.required' => 'The Firebase service account JSON file is required.',
            'firebase_service_account.file' => 'The Firebase service account must be a file.',
            'firebase_service_account.mimes' => 'The Firebase service account must be a JSON file.',
            'firebase_service_account.max' => 'The Firebase service account file may not be greater than 2MB.',
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