<?php

namespace Database\Seeders;

use App\Models\SocialPlatform;
use Illuminate\Database\Seeder;

class SocialPlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            ['name' => 'Instagram', 'slug' => 'instagram', 'base_url' => 'https://instagram.com/', 'icon' => 'fa-brands fa-instagram'],
            ['name' => 'Facebook', 'slug' => 'facebook', 'base_url' => 'https://facebook.com/', 'icon' => 'fa-brands fa-facebook'],
            ['name' => 'X (Twitter)', 'slug' => 'x-twitter', 'base_url' => 'https://x.com/', 'icon' => 'fa-brands fa-x-twitter'],
            ['name' => 'LinkedIn', 'slug' => 'linkedin', 'base_url' => 'https://linkedin.com/in/', 'icon' => 'fa-brands fa-linkedin'],
            ['name' => 'YouTube', 'slug' => 'youtube', 'base_url' => 'https://youtube.com/', 'icon' => 'fa-brands fa-youtube'],
            ['name' => 'TikTok', 'slug' => 'tiktok', 'base_url' => 'https://tiktok.com/@', 'icon' => 'fa-brands fa-tiktok'],
            ['name' => 'GitHub', 'slug' => 'github', 'base_url' => 'https://github.com/', 'icon' => 'fa-brands fa-github'],
            ['name' => 'WhatsApp', 'slug' => 'whatsapp', 'base_url' => 'https://wa.me/', 'icon' => 'fa-brands fa-whatsapp'],
        ];

        foreach ($platforms as $platform) {
            SocialPlatform::query()->firstOrCreate(
                ['slug' => $platform['slug']],
                $platform + ['is_active' => true]
            );
        }
    }
}
