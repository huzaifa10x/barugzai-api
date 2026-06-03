<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SocialMediaLink;

class SocialMediaLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialMediaLink::create([
            'facebook' => 'https://facebook.com/your-page',
            'instagram' => 'https://instagram.com/your-page',
            'x' => 'https://x.com/your-page',
            'tiktok' => 'https://tiktok.com/@your-page',
            'youtube' => 'https://youtube.com/your-channel',
        ]);
    }
}
