<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            // User Account
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($userId)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'profile_image' => ['nullable', 'image', 'max:5120'],
            'gender' => ['nullable', 'in:male,female,other'],
            'current_weight' => ['nullable', 'numeric'],
            'weight_unit' => ['nullable', 'in:kg,lbs'],
            'current_height' => ['nullable', 'numeric'],
            'height_unit' => ['nullable', 'in:cm,ft'],
            'main_goal' => ['nullable', 'string'],
            'fitness_experience' => ['nullable', 'string'],
            'diet_preference' => ['nullable', 'string'],
            'medical_issues' => ['nullable', 'string', 'max:1000'],
            'medical_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}