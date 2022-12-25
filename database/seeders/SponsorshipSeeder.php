<?php

namespace Database\Seeders;

use App\Models\Sponsorship;
use Illuminate\Database\Seeder;

class SponsorshipSeeder extends Seeder
{
    public function run()
    {
        Sponsorship::factory()->count(100)->create();
    }
}
