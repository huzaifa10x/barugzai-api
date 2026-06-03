<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AboutOurCompany;

class AboutOurCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutOurCompany::create([
            'value' => 'We are a premier company offering excellent services to our clients.',
        ]);
    }
}
