<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\SectionSeeder;
use Database\Seeders\WhatWeOfferSeeder;
use Database\Seeders\AboutUsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SectionSeeder::class);
        $this->call(WhatWeOfferSeeder::class);
        $this->call(AboutUsSeeder::class);

    }
}
