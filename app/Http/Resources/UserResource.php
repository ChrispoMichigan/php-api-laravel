<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'User',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Christian Castillo'),
        new OA\Property(property: 'username', type: 'string', nullable: true, example: 'christian'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'christian@example.com'),
        new OA\Property(
            property: 'social_links',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/UserSocialLink')
        ),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'social_links' => UserSocialLinkResource::collection($this->whenLoaded('socialLinks')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
