<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSocialPlatformRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $socialPlatformId = $this->route('social_platform')->id;

        return [
            'name' => ['sometimes', 'string', 'max:50'],
            'slug' => ['sometimes', 'string', 'alpha_dash', 'max:50', Rule::unique('social_platforms', 'slug')->ignore($socialPlatformId)],
            'base_url' => ['nullable', 'string', 'url', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
