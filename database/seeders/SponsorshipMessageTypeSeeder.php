<?php

namespace Database\Seeders;

use App\Models\SponsorshipMessageType;
use Illuminate\Database\Seeder;

class SponsorshipMessageTypeSeeder extends Seeder
{
    public function run()
    {
        SponsorshipMessageType::factory()->createOne([
            'name' => 'Prvi pozdrav 1',
            'subject' => 'Prvi pozdrav 1 (dobrodošlica)',
            'template_id' => 'prvi_pozdrav_1',
            'is_active' => true,
        ]);
        SponsorshipMessageType::factory()->createOne([
            'name' => 'Prvi pozdrav 2',
            'subject' => 'Prvi pozdrav 2 (za obstoječe botre)',
            'template_id' => 'prvi_pozdrav_2',
            'is_active' => true,
        ]);
        SponsorshipMessageType::factory()->createOne([
            'name' => 'Prvi pozdrav 3',
            'subject' => 'Prvi pozdrav 3',
            'template_id' => 'prvi_pozdrav_3',
            'is_active' => true,
        ]);
        SponsorshipMessageType::factory()->createOne([
            'name' => 'Bubiji - prvi pozdrav',
            'subject' => 'Bubiji - prvi pozdrav',
            'template_id' => 'bubiji_prvi_pozdrav',
            'is_active' => true,
        ]);
        SponsorshipMessageType::factory()->createOne([
            'name' => 'Pozitivčki - prvi pozdrav',
            'subject' => 'Pozitivčki - prvi pozdrav',
            'template_id' => 'pozitivcki_prvi_pozdrav',
            'is_active' => true,
        ]);
        SponsorshipMessageType::factory()->createOne([
            'name' => 'Čombe - prvi pozdrav',
            'subject' => 'Čombe - prvi pozdrav',
            'template_id' => 'combe_prvi_pozdrav',
            'is_active' => true,
        ]);
    }
}
