<?php

namespace Database\Factories;

use App\Models\SocialPlatform;
use App\Models\User;
use App\Models\UserSocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserSocialLink>
 */
class UserSocialLinkFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'social_platform_id' => SocialPlatform::factory(),
            'url' => fake()->url(),
            'handle' => '@' . fake()->userName(),
            'title' => fake()->optional()->sentence(3),
            'position' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
