<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class TicketReplyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'message' => ['required', 'string', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'message.required' => 'Reply message is required',
            'message.min' => 'Reply message cannot be empty',
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