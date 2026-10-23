<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSocialLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'social_platform_id' => ['sometimes', 'integer', 'exists:social_platforms,id'],
            'url' => ['sometimes', 'string', 'url', 'max:255'],
            'handle' => ['nullable', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:100'],
            'position' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
