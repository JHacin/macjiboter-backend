<?php

namespace Database\Seeders;

use App\Models\SponsorshipMessage;
use Illuminate\Database\Seeder;

class SponsorshipMessageSeeder extends Seeder
{
    public function run()
    {
        SponsorshipMessage::factory()->count(10)->create();
    }
}
