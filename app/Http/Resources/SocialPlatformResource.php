<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SocialPlatform',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Instagram'),
        new OA\Property(property: 'slug', type: 'string', example: 'instagram'),
        new OA\Property(property: 'base_url', type: 'string', nullable: true, example: 'https://instagram.com/'),
        new OA\Property(property: 'icon', type: 'string', nullable: true, example: 'fa-brands fa-instagram'),
        new OA\Property(property: 'is_active', type: 'boolean', example: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class SocialPlatformResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'base_url' => $this->base_url,
            'icon' => $this->icon,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
