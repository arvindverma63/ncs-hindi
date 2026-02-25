<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class DriverRequest extends FormRequest
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
            'first_name' => 'required|string|max:200',
            'last_name' => 'nullable|string|max:200',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required',
            'gender' => 'required|integer',
            'nationality' => 'required|integer',
            'state' => 'required|string|max:20',
            'bank_name' => 'required|string|max:200',
            'bank_account_number' => 'required|integer',
            'bank_branch_name' => 'required|string|max:20',
            'bank_passbook_photo' => 'required|image|max:2048',
            'license_issuing_parish' => 'required|string|max:200',
            'license_number' => 'required|string|max:200',
            'license_issuing_date' => 'required',
            'license_expire_date' => 'required',
            'license_photo' => 'required|image|max:2048',
            'number_plate' => 'required',
            'insurance_certificate' => 'required|image|max:2048',
            'fitness_certificate' => 'required|image|max:2048',
            'registration_certificate' => 'required|image|max:2048',

            'profile_image' => 'nullable|image|max:2048',
            'vehicle_type_id' => 'required|string|exists:vehicles,id',
            'vehicle_sub_type_id' =>  'required|string|exists:vehicles,id',
            'vehicle_image' => 'required|image|max:2048',
            'password' => 'required|string|min:8|confirmed',
            'is_verified' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    { 

        return [
            'first_name.required' => 'First Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email is already taken',
            'phone.required' => 'Phone Number is required',
            'profile_image.image' => 'Profile image must be an image',
            'profile_image.max' => 'Profile image may not be greater than 2MB',
            'vehicle_image.image' => 'Vehicle image must be an image',
            'vehicle_image.max' => 'Vehicle image may not be greater than 2MB',
            'bank_passbook_photo.image' => 'Bank passbook photo must be an image',
            'bank_passbook_photo.max' => 'Bank passbook photo may not be greater than 2MB',
            'license_photo.image' => 'License photo must be an image',
            'license_photo.max' => 'License photo may not be greater than 2MB',
            'vehicle_type_id.required' => 'Vehicle Type is required',
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
