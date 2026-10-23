<?php

namespace App\Models;

use Database\Factories\UserSocialLinkFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'social_platform_id', 'url', 'handle', 'title', 'position', 'is_active'])]
class UserSocialLink extends Model
{
    /** @use HasFactory<UserSocialLinkFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function socialPlatform(): BelongsTo
    {
        return $this->belongsTo(SocialPlatform::class);
    }
}
