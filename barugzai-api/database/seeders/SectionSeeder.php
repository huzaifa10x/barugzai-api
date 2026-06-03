<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section;
class SectionSeeder extends Seeder
{
    public function run(): void
    {
        Section::create([
            'new_arrival_title' => 'New Arrivals',
            'new_arrival_description' => 'Barugzai Motors offers a premier selection of luxury vehicles, including the refined Mercedes V-Class and spacious Sprinter. We make it easy for clients to find exactly what they want, with a commitment to quality, style, and exceptional service. Discover your perfect luxury vehicle with us at Barugzai Motors.',
            'explore_service_title' => 'Explore Our Services',
            'explore_service_subtitle' => 'Modify, Adjust, Make, WE ARE HERE FOR YOU',
            'explore_service_description' => 'Barugzai Motors offers a premier selection of luxury vehicles, including the refined Mercedes V-Class and spacious Sprinter. We make it easy for clients to find exactly what they want, with a commitment to quality, style, and exceptional service. Discover your perfect luxury vehicle with us at Barugzai Motors.',
            'videos_title' => 'Our Videos',
            'videos_description' => 'Barugzai Motors offers a premier selection of luxury vehicles, including the refined Mercedes V-Class and spacious Sprinter. We make it easy for clients to find exactly what they want, with a commitment to quality, style, and exceptional service. Discover your perfect luxury vehicle with us at Barugzai Motors.',
            'youtube_video_1' => 'https://www.youtube.com/watch?v=szREnYtUTGM',
            'youtube_video_2' => 'https://www.youtube.com/watch?v=szREnYtUTGM',
            'youtube_video_3' => 'https://www.youtube.com/watch?v=szREnYtUTGM',
            'youtube_video_4' => 'https://www.youtube.com/watch?v=szREnYtUTGM',
        ]);
    }
}
