<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSocialPlatformRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'slug' => ['required', 'string', 'alpha_dash', 'max:50', 'unique:social_platforms,slug'],
            'base_url' => ['nullable', 'string', 'url', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
