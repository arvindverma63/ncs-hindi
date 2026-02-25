<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateMapConfigureRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'google_maps_api_key' => 'required|string|max:255',
            'mapbox_access_token' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'google_maps_api_key.required' => 'The Google Maps API Key is required.',
            'google_maps_api_key.string' => 'The Google Maps API Key must be a string.',
            'google_maps_api_key.max' => 'The Google Maps API Key cannot exceed 255 characters.',
            'mapbox_access_token.string' => 'The Mapbox Access Token must be a string.',
            'mapbox_access_token.max' => 'The Mapbox Access Token cannot exceed 255 characters.',
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