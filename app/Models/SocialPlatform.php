<?php

namespace App\Models;

use Database\Factories\SocialPlatformFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'base_url', 'icon', 'is_active'])]
class SocialPlatform extends Model
{
    /** @use HasFactory<SocialPlatformFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function userSocialLinks(): HasMany
    {
        return $this->hasMany(UserSocialLink::class);
    }
}
