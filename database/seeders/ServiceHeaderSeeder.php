<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceHeader;

class ServiceHeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceHeader::create([
            'header_text' => 'Explore Our Premium Services for Your Luxury Cars'
        ]);
    }
}
