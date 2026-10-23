<?php

namespace Database\Factories;

use App\Models\SocialPlatform;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SocialPlatform>
 */
class SocialPlatformFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => $name,
            'base_url' => 'https://' . $name . '.com/',
            'icon' => $name,
            'is_active' => true,
        ];
    }
}
