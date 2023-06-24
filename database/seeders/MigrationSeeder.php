<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\PersonData;
use App\Models\User;
use App\Settings\Settings;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
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
        $this->addSponsors();
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
                "name" => $this->parseNullableString($record["ime"]),
                "story_short" => "",
                "status" => $status,
                "gender" => $gender,
                "story" => $this->parseNullableString($record["zgodba"]),
            ]);

            $this->storeRecordMigrationMeta("cat", $entry, $record);
        }
    }

    /**
     * @throws Exception
     */
    protected function addSponsors(): void
    {
        $csvUrl = Storage::disk('s3')->url('migration/mb_macji_botri.csv');
        $records = $this->importCsv($csvUrl);

        foreach ($records as $record) {
            $entry = PersonData::create([
                "email" => $this->parseNullableString($record["email"]),
                "gender" => $record["spol"] === "M" ? PersonData::GENDER_MALE : PersonData::GENDER_FEMALE,
                "first_name" => $this->parseNullableString($record["ime"]),
                "last_name" => $this->parseNullableString($record["priimek"]),
                "date_of_birth" => $this->parseNullableDate($record["datum_rojstva"]),
                "address" => $this->parseNullableString($record["naslov"]),
                "zip_code" => $this->parseNullableString($record["postna_st"]),
                "city" => $this->parseNullableString($record["kraj"]),
            ]);

            $this->storeRecordMigrationMeta("sponsor", $entry, $record);
        }
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

    protected function storeRecordMigrationMeta(string $entity, Cat|PersonData $model, array $csvRecord): void
    {
        DB::table("db_migration_meta")->insert([
            "entity" => $entity,
            "new_id" => $model->id,
            "prev_id" => $csvRecord["id"],
            "prev_data" => json_encode($csvRecord),
        ]);
    }

    protected function parseNullableString(string $value): string|null
    {
        return $value === "NULL" ? null : $value;
    }

    protected function parseNullableDate(string $value): string|null
    {
        $dateString = $this->parseNullableString($value);

        if (
            !$dateString ||
            Carbon::parse($dateString)->year < 1 // When stored in the prev DB as 0000-00-00
        ) {
            return null;
        }

        return $dateString;
    }
}
