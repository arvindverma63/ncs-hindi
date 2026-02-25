<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'country_code' => 'required',
            'phone' => 'required|unique:users|string|max:15',
            'password' => 'required|confirmed|min:6',
            'gender' => 'required',
            'user_type' => 'required',
            'identify_yourself' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be valid.',
            'email.unique' => 'Email is already taken.',
            'country_code.required' => 'Country Code is required.',
            'phone.required' => 'Phone number is required.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 6 characters.',
            'gender.required' => 'Gender is required.',
            'user_type.required' => 'User type is required.',
            'identify_yourself.required' => 'Identify Yourself is required.',
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