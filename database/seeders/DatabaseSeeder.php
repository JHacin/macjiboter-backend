<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            SettingsSeeder::class,
            PersonDataSeeder::class,
            UserSeeder::class,
            CatLocationSeeder::class,
            CatSeeder::class,
            SponsorshipSeeder::class,
            SpecialSponsorshipSeeder::class,
            SponsorshipMessageTypeSeeder::class,
            SponsorshipMessageSeeder::class,
            NewsSeeder::class,
        ]);
    }
}
