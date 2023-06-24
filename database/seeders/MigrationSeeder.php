<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\User;
use App\Settings\Settings;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Storage;

class MigrationSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run(): void
    {
        $this->addAdminUser();
        $this->addSettings();
        $this->addCats();
    }

    protected function addAdminUser(): void
    {
        /** @var User $user */
        $user = User::factory()->createOne([
            'email' => 'jan.hacin@gmail.com',
            'password' => User::generateSecurePassword('RJWcO3fQQi05')
        ]);

        $user->assignRole(User::ROLE_SUPER_ADMIN);
    }

    protected function addSettings(): void
    {
        DB::table('settings')->insert([
            'key' => Settings::KEY_ENABLE_EMAILS,
            'name' => 'Omogoči pošiljanje mailov',
            'value' => Settings::VALUE_FALSE,
            'field' => '{"name":"value","label":"Veljavno?","type":"checkbox"}',
            'active' => 1,
        ]);

        DB::table('settings')->insert([
            'key' => Settings::KEY_ENABLE_MAILING_LISTS,
            'name' => 'Omogoči mailing sezname',
            'value' => Settings::VALUE_FALSE,
            'field' => '{"name":"value","label":"Veljavno?","type":"checkbox"}',
            'active' => 1,
        ]);
    }

    /**
     * @throws Exception
     */
    protected function importCsv($url): bool|array
    {
        if (!$url) {
            throw new Exception("CSV URL empty");
        }

        $header = null;
        $data = array();

        if (($handle = fopen($url, "r")) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * @throws Exception
     */
    protected function addCats(): void
    {
        $csvUrl = Storage::disk('s3')->url('migration/oglasi_muce.csv');
        $records = $this->importCsv($csvUrl);

        foreach ($records as $record) {
            if (
                $record["posvojitev_na_daljavo"] !== "1" &&
                ($record["status"] === "4" || $record["status"] === "5")
            ) {
                continue;
            }

            $status = match ($record["status"]) {
                // Oddaja se prvič || Oddaja se ponovno
                "1", "2" => Cat::STATUS_SEEKING_SPONSORS,
                // Se ne oddaja oz. bo ostal nedoločen čas
                "3" => Cat::STATUS_NOT_SEEKING_SPONSORS,
                // V novem domu
                "4" => Cat::STATUS_ADOPTED,
                // RIP
                "5" => Cat::STATUS_RIP
            };

            $gender = match ($record["spol"]) {
                // samček
                "1", => Cat::GENDER_MALE,
                // samička
                "2" => Cat::GENDER_FEMALE,
                // samček, samička || neznano
                "3", "4" => null,
            };


            $entry = Cat::create([
                "name" => $record["ime"],
                "story_short" => "",
                "status" => $status,
                "gender" => $gender,
                "story" => $record["zgodba"] === "NULL" ? null : $record["zgodba"],
            ]);

            DB::table("db_migration_meta")->insert([
                "entity" => "cat",
                "new_id" => $entry->id,
                "prev_id" => $record["id"],
                "prev_data" => json_encode($record),
            ]);
        }
    }
}
