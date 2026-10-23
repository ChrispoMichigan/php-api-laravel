<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserSocialLink',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'user_id', type: 'integer', example: 1),
        new OA\Property(property: 'social_platform_id', type: 'integer', example: 1),
        new OA\Property(property: 'url', type: 'string', example: 'https://instagram.com/christian'),
        new OA\Property(property: 'handle', type: 'string', nullable: true, example: '@christian'),
        new OA\Property(property: 'title', type: 'string', nullable: true, example: 'Mi Instagram'),
        new OA\Property(property: 'position', type: 'integer', example: 0),
        new OA\Property(property: 'is_active', type: 'boolean', example: true),
        new OA\Property(property: 'social_platform', ref: '#/components/schemas/SocialPlatform', type: 'object'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class UserSocialLinkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'social_platform_id' => $this->social_platform_id,
            'url' => $this->url,
            'handle' => $this->handle,
            'title' => $this->title,
            'position' => $this->position,
            'is_active' => $this->is_active,
            'social_platform' => new SocialPlatformResource($this->whenLoaded('socialPlatform')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
