<?php

namespace Database\Seeders;

use App\Models\SpecialSponsorship;
use Illuminate\Database\Seeder;

class SpecialSponsorshipSeeder extends Seeder
{
    public function run()
    {
        SpecialSponsorship::factory()->count(1000)->create();
    }
}
