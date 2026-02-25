<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'dropoff_lat' => 'required',
            'dropoff_lng' => 'required', 
            'miles' => 'required',
            'order_id' => 'required',
        ];
    }

    public function messages(): array
    { 

        return [
            'user_id.required'       => 'User Id is required.',    
            'pickup_lat.required'       => 'Pickup Lat is required.',    
            'pickup_lng.required'       => 'Pickup Long is required.',    
            'dropoff_lat.required'       => 'Drop Lat is required.',    
            'dropoff_lng.required'       => 'Drop Long is required.',    
            'miles.required'       => 'Miles is required.',  
            'order_id.required'       => 'Order Id is required.',  
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
