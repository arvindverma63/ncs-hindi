<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreInteractionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coach_id' => 'required|exists:users,id', // Must be a valid Coach User ID
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
            // 'seeker_id' is usually injected from Auth::id() in the controller, so we don't validate it here
        ];
    }
}