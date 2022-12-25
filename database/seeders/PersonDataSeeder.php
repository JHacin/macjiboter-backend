<?php

namespace Database\Seeders;

use App\Models\PersonData;
use App\Models\Sponsorship;
use Illuminate\Database\Seeder;

class PersonDataSeeder extends Seeder
{
    public function run()
    {
        PersonData::factory()
            ->has(
                Sponsorship::factory()
                    ->count(10)
                    ->sequence(
                        ['is_active' => true],
                        ['is_active' => false]
                    )
            )->createOne();
    }
}
