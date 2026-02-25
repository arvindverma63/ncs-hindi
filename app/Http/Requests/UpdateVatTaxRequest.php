<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateVatTaxRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => ['required', Rule::in(['order_base', 'item_base', 'service_base'])],
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'deduction' => ['required', Rule::in(['exclusive', 'inclusive'])],
            'is_active' => 'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'The tax type is required.',
            'type.in' => 'The tax type must be one of: order_base, item_base, service_base.',
            'name.required' => 'The tax name is required.',
            'name.max' => 'The tax name must not exceed 255 characters.',
            'percentage.required' => 'The tax percentage is required.',
            'percentage.numeric' => 'The tax percentage must be a number.',
            'percentage.min' => 'The tax percentage must be at least 0.',
            'percentage.max' => 'The tax percentage must not exceed 100.',
            'deduction.required' => 'The deduction type is required.',
            'deduction.in' => 'The deduction type must be either exclusive or inclusive.',
            'is_active.required' => 'The status is required.',
            'is_active.in' => 'The status must be either Active or Inactive.',
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