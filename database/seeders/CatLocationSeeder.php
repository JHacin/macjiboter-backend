<?php

namespace Database\Seeders;

use App\Models\CatLocation;
use Illuminate\Database\Seeder;

class CatLocationSeeder extends Seeder
{
    public function run()
    {
        CatLocation::factory()->count(25)->create();
    }
}
