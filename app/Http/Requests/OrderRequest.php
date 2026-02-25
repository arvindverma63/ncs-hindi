<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class OrderRequest extends FormRequest
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
            'vehicle_type_id' => 'required|string|exists:vehicles,id',
            'driver_id' => 'nullable|string|exists:driver_details,id',
            'goods_type_id' => 'required|string|exists:categories,id',
            'pickup_location_add' => 'required|string|max:200',
            'pickup_location_lat' => 'nullable|string',
            'pickup_location_lng' => 'nullable|string',
            'pickup_name' => 'nullable|string|max:200',
            'pickup_phone' => 'nullable|string|max:20',
            'drop_location_add' => 'required|string|max:200',
            'drop_location_lat' => 'required|string',
            'drop_location_lng' => 'required|string',
            'drop_name' => 'nullable|string|max:200',
            'drop_phone' => 'nullable|string|max:20',
            'weight' => 'required|string|max:200',
            'quantity' => 'required|integer|min:1',
            'tax_amount' => 'required|integer|min:0',
            'payable_amount' => 'required|integer|min:0',
            'total_amount' => 'required|integer|min:0',
            'payment_method' => 'required',
            'payment_status' => 'required|in:0,1',
            'order_status' => 'required|in:1,2,3,4',
            'instruction' => 'nullable|string|max:200',
        ];
    }

    public function messages(): array
    { 

        return [
            'vehicle_type_id.required' => 'Vehicle type is required.',
            'vehicle_type_id.exists' => 'Invalid vehicle type.',
            'driver_id.exists' => 'Driver not found.',
            'goods_type_id.required' => 'Goods type is required.',
            'goods_type_id.exists' => 'Invalid goods type.',
            'pickup_location_add.required' => 'Pickup location is required.',
            'drop_location_add.required' => 'Drop location is required.',
            'weight.required' => 'Package weight is required.',
            'quantity.required' => 'Quantity is required.',
            'payment_method.required' => 'Payment method is required.',
            'order_status.required' => 'Order status is required.',
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
