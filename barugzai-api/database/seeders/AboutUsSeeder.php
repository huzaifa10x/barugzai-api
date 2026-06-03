<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AboutUs;
use App\Models\AboutUsImage;
use Illuminate\Support\Facades\Storage;
class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Clear old data
       AboutUs::truncate();
       AboutUsImage::truncate();
       Storage::disk('public')->deleteDirectory('about_us'); // Delete old images

       // Create About Us record
       $aboutUs = AboutUs::create([
           'text_header_1' => 'About Our Company',
           'description_1' => 'We are a leading automotive company specializing in high-end cars.',
           'text_1' => 'Your Trusted Partner for Premium Cars.',
           'points' => json_encode([
               'Best Customer Support',
               'Affordable Pricing',
               'Luxury Car Selection',
               'Worldwide Shipping'
           ]),
           'header_2' => 'Our Vision',
           'description_2' => 'Delivering Excellence in the Automotive Industry.',
           'header_3' => 'Our Mission',
           'description_3' => 'Providing high-quality vehicles with top-notch service.'
       ]);

       // Ensure at least 5 images
       for ($i = 1; $i <= 5; $i++) {
           AboutUsImage::create([
               'about_us_id' => $aboutUs->id,
               'url' => 'about_us/sample' . $i . '.jpg'
           ]);
       }
    }
}
