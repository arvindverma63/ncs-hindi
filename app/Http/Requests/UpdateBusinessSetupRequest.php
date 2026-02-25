<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateBusinessSetupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'active_tab' => 'required|in:instant,pending',
        ];

        if ($this->input('active_tab') === 'instant') {
            $rules = array_merge($rules, [
                'instant_min_withdrawal' => 'required|numeric|min:0',
                'instant_max_withdrawal' => 'required|numeric|gt:instant_min_withdrawal',
                'instant_admin_commission_charge' => 'required|numeric|min:0',
                'pending_min_withdrawal' => 'nullable|numeric|min:0',
                'pending_max_withdrawal' => 'nullable|numeric|gt:pending_min_withdrawal',
            ]);
        } elseif ($this->input('active_tab') === 'pending') {
            $rules = array_merge($rules, [
                'pending_min_withdrawal' => 'required|numeric|min:0',
                'pending_max_withdrawal' => 'required|numeric|gt:pending_min_withdrawal',
                'instant_min_withdrawal' => 'nullable|numeric|min:0',
                'instant_max_withdrawal' => 'nullable|numeric|gt:instant_min_withdrawal',
                'instant_admin_commission_charge' => 'nullable|numeric|min:0',
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'active_tab.required' => 'The active tab is required.',
            'active_tab.in' => 'The active tab must be either instant or pending.',
            'instant_min_withdrawal.required' => 'The min withdrawal for instant is required.',
            'instant_min_withdrawal.numeric' => 'The min withdrawal for instant must be a number.',
            'instant_min_withdrawal.min' => 'The min withdrawal for instant cannot be negative.',
            'instant_max_withdrawal.required' => 'The max withdrawal for instant is required.',
            'instant_max_withdrawal.numeric' => 'The max withdrawal for instant must be a number.',
            'instant_max_withdrawal.gt' => 'The max withdrawal for instant must be greater than min withdrawal.',
            'instant_admin_commission_charge.required' => 'The admin commission charge for instant is required.',
            'instant_admin_commission_charge.numeric' => 'The admin commission charge for instant must be a number.',
            'instant_admin_commission_charge.min' => 'The admin commission charge for instant cannot be negative.',
            'pending_min_withdrawal.required' => 'The min withdrawal for pending is required.',
            'pending_min_withdrawal.numeric' => 'The min withdrawal for pending must be a number.',
            'pending_min_withdrawal.min' => 'The min withdrawal for pending cannot be negative.',
            'pending_max_withdrawal.required' => 'The max withdrawal for pending is required.',
            'pending_max_withdrawal.numeric' => 'The max withdrawal for pending must be a number.',
            'pending_max_withdrawal.gt' => 'The max withdrawal for pending must be greater than min withdrawal.',
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