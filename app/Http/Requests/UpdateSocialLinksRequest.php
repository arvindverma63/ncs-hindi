<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateSocialLinksRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'facebook_url' => 'nullable|url|max:255',
            'x_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
        ];
    }

    public function messages()
    {
        return [
            'facebook_url.url' => 'The Facebook URL must be a valid URL.',
            'facebook_url.max' => 'The Facebook URL cannot exceed 255 characters.',
            'x_url.url' => 'The X URL must be a valid URL.',
            'x_url.max' => 'The X URL cannot exceed 255 characters.',
            'instagram_url.url' => 'The Instagram URL must be a valid URL.',
            'instagram_url.max' => 'The Instagram URL cannot exceed 255 characters.',
            'whatsapp_url.url' => 'The WhatsApp URL must be a valid URL.',
            'whatsapp_url.max' => 'The WhatsApp URL cannot exceed 255 characters.',
            'youtube_url.url' => 'The YouTube URL must be a valid URL.',
            'youtube_url.max' => 'The YouTube URL cannot exceed 255 characters.',
            'linkedin_url.url' => 'The LinkedIn URL must be a valid URL.',
            'linkedin_url.max' => 'The LinkedIn URL cannot exceed 255 characters.',
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