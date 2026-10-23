<?php

namespace Database\Seeders;

use App\Models\SocialPlatform;
use App\Models\User;
use App\Models\UserSocialLink;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SocialPlatformSeeder::class);

        User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        User::factory(10)
            ->create()
            ->each(function (User $user) {
                UserSocialLink::factory(fake()->numberBetween(2, 5))->create([
                    'user_id' => $user->id,
                    'social_platform_id' => SocialPlatform::query()->inRandomOrder()->first()->id,
                ]);
            });
    }
}
