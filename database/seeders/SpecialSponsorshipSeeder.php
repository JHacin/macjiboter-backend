<?php

namespace Database\Seeders;

use App\Models\SpecialSponsorship;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SpecialSponsorshipSeeder extends Seeder
{
    public function run()
    {
        SpecialSponsorship::factory()->count(50)->create();
        SpecialSponsorship::factory(['confirmed_at' => Carbon::now()])->count(25)->create();
    }
}
