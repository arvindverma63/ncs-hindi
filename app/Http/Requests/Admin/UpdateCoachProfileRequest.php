<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoachProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gender' => 'nullable|in:male,female,other',
            'show_personal_details' => 'boolean',
            'company_name' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'linkedin_url' => 'nullable|url|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'bio' => 'nullable|string',
            'is_visible' => 'boolean',
            'is_featured' => 'boolean',
            'approval_status' => 'sometimes|in:pending,approved,rejected',
            
            'categories' => 'nullable|array',
            'categories.*' => 'nullable|string' // Removed 'exists' to allow new tag strings
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'show_personal_details' => $this->has('show_personal_details') ? 1 : 0,
            'is_featured' => $this->has('is_featured') ? 1 : 0,
            'is_visible' => $this->has('is_visible') ? 1 : 0,
        ]);
    }
}