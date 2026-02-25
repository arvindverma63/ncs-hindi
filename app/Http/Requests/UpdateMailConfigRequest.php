<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateMailConfigRequest extends FormRequest
{

    public function authorize()
    {
        
        return true;
    }


    public function rules()
    {
        return [
            'mailer_name' => 'required|string|max:255',
            'mail_host' => 'required|string|max:255',
            'mail_driver' => 'required|in:smtp,mailgun,ses',
            'mail_port' => 'required|numeric',
            'mail_username' => 'required|string|max:255',
            'mail_email_id' => 'required|email|max:255',
            'mail_encryption' => 'required|in:tls,ssl,none',
            'mail_password' => 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'mailer_name.required' => 'The mailer name is required.',
            'mailer_name.max' => 'The mailer name cannot exceed 255 characters.',
            'mail_host.required' => 'The mail host is required.',
            'mail_host.max' => 'The mail host cannot exceed 255 characters.',
            'mail_driver.required' => 'The mail driver is required.',
            'mail_driver.in' => 'The selected mail driver is invalid.',
            'mail_port.required' => 'The mail port is required.',
            'mail_port.numeric' => 'The mail port must be a number.',
            'mail_username.required' => 'The mail username is required.',
            'mail_username.max' => 'The mail username cannot exceed 255 characters.',
            'mail_email_id.required' => 'The email ID is required.',
            'mail_email_id.email' => 'The email ID must be a valid email address.',
            'mail_email_id.max' => 'The email ID cannot exceed 255 characters.',
            'mail_encryption.required' => 'The encryption type is required.',
            'mail_encryption.in' => 'The selected encryption type is invalid.',
            'mail_password.required' => 'The mail password is required.',
            'mail_password.min' => 'The mail password must be at least 6 characters.',
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