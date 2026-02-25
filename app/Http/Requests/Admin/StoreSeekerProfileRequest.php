<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeekerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // User Account Data (Optional if creating user separately)
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',

            // Seeker Profile Data
            'business_domain' => 'nullable|string|max:255', // e.g., "Real Estate"
            'company_name' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            
            // Preferences
            'notification_preferences' => 'nullable|array',
            'is_verified' => 'boolean'
        ];
    }
}