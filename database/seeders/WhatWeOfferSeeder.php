<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WhatWeOffer;

class WhatWeOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WhatWeOffer::create([
            'description' => 'Our services are unparalleled, offering the best solutions for your needs.',
            'images' => [],
        ]);
    }
}
