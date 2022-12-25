<?php

namespace Database\Seeders;

use App\Settings\Settings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'key' => Settings::KEY_ENABLE_EMAILS,
                'name' => 'Omogoči pošiljanje mailov',
                'value' => Settings::VALUE_FALSE,
                'field' => '{"name":"value","label":"Veljavno?","type":"checkbox"}',
                'active' => 1,
            ],
            [
                'key' => Settings::KEY_ENABLE_MAILING_LISTS,
                'name' => 'Omogoči mailing sezname',
                'value' => Settings::VALUE_FALSE,
                'field' => '{"name":"value","label":"Veljavno?","type":"checkbox"}',
                'active' => 1,
            ],
        ];

        foreach ($settings as $index => $setting) {
            $result = DB::table('settings')->insert($setting);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");

                return;
            }
        }
    }
}
