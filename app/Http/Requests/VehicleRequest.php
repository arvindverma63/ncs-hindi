<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class VehicleRequest extends FormRequest
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
            'vehicle_type' => 'required',
            'capacity' => 'required',
            'dimention' => 'required',
            'base_delivery_distance' => 'required',
            'base_delivery_price' => 'required',
            'vehicle_image' => 'nullable|image|max:2048',
            'description' => 'nullable',
        ];
    }

    public function messages(): array
    { 

        return [
            'vehicle_type.required' => 'Vehicle Type is required',
            'capacity.required' => 'Capacity is required',
            'dimention.required' => 'Dimension is required',
            'base_delivery_distance.required' => 'Base Delivery Distance is required',
            'base_delivery_price.required' => 'Base Delivery Price is required',
            'vehicle_image.image' => 'The uploaded file must be an image',
            'vehicle_image.max' => 'The image may not be greater than 2MB',
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
