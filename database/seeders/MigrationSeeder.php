<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\PersonData;
use App\Models\Sponsorship;
use App\Models\User;
use App\Settings\Settings;
use Arr;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Storage;

class MigrationSeeder extends Seeder
{
    public array $catMigrationCache = [];

    public array $sponsorMigrationCache = [];

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $this->addAdminUser();
        $this->addSettings();
        $this->addCats();
        $this->addSponsors();
        $this->addSponsorships();
    }

    protected function addAdminUser(): void
    {
        /** @var User $user */
        $user = User::factory()->createOne([
            'email' => 'jan.hacin@gmail.com',
            'password' => User::generateSecurePassword(config('auth.default_super_admin_password')),
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
                $record['posvojitev_na_daljavo'] !== '1' &&
                ($record['status'] === '4' || $record['status'] === '5')
            ) {
                continue;
            }

            $status = match ($record['status']) {
                // Oddaja se prvič || Oddaja se ponovno
                '1', '2' => Cat::STATUS_SEEKING_SPONSORS,
                // Se ne oddaja oz. bo ostal nedoločen čas
                '3' => Cat::STATUS_NOT_SEEKING_SPONSORS,
                // V novem domu
                '4' => Cat::STATUS_ADOPTED,
                // RIP
                '5' => Cat::STATUS_RIP
            };

            $gender = match ($record['spol']) {
                // samček
                '1', => Cat::GENDER_MALE,
                // samička
                '2' => Cat::GENDER_FEMALE,
                // samček, samička || neznano
                '3', '4' => null,
            };

            $entry = Cat::create([
                'name' => $this->parseNullableString($record['ime']),
                'status' => $status,
                'gender' => $gender,
                'story' => $this->parseNullableString($record['zgodba']),
                'is_published' => $record['objavi_zgodbo'] === '1',
                'created_at' => $this->parseNullableDate($record['add_date']),
                'updated_at' => $this->parseNullableDate($record['last_update']),
            ]);

            $this->catMigrationCache[$record['id']] = ['new_id' => $entry->id];

            $this->persistRecordMigrationMeta('cat', $entry, $record);
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
                'email' => $this->parseNullableString($record['email']),
                'gender' => $record['spol'] === 'M' ? PersonData::GENDER_MALE : PersonData::GENDER_FEMALE,
                'first_name' => $this->parseNullableString($record['ime']),
                'last_name' => $this->parseNullableString($record['priimek']),
                'date_of_birth' => $this->parseNullableDate($record['datum_rojstva']),
                'address' => $this->parseNullableString($record['naslov']),
                'zip_code' => $this->parseNullableString($record['postna_st']),
                'city' => $this->parseNullableString($record['kraj']),
                'created_at' => $this->parseNullableDate($record['datum_pristopa']),
                'updated_at' => $this->parseNullableDate($record['datum_pristopa']),
            ]);

            $this->sponsorMigrationCache[$record['id']] = [
                'new_id' => $entry->id,
                'datum_pristopa' => $record['datum_pristopa'],
                'potrjen_pristop' => $record['potrjen_pristop'],
                'trenutno_aktiven' => $record['trenutno_aktiven'],
            ];

            $this->persistRecordMigrationMeta('sponsor', $entry, $record);
        }
    }

    /**
     * @throws Exception
     */
    protected function addSponsorships(): void
    {
        $csvUrl = Storage::disk('s3')->url('migration/mb_posvojitve.csv');
        $records = $this->importCsv($csvUrl);

        foreach ($records as $record) {
            $catEntry = Arr::get($this->catMigrationCache, $record['id_muce']);
            $sponsorEntry = Arr::get($this->sponsorMigrationCache, $record['id_botra']);
            $endedAt = $this->parseNullableDate($record['datum_konca']);

            $isActive = $sponsorEntry &&
                $endedAt === null &&
                $this->parseNullableDate($sponsorEntry['datum_pristopa']) !== null &&
                $this->parseBoolean($sponsorEntry['potrjen_pristop']) &&
                $this->parseBoolean($sponsorEntry['trenutno_aktiven']);

            $entry = Sponsorship::create([
                'cat_id' => $catEntry ? $catEntry['new_id'] : null,
                'sponsor_id' => $sponsorEntry ? $sponsorEntry['new_id'] : null,
                'is_anonymous' => $this->parseBoolean($record['anonimno']),
                'monthly_amount' => (int) $record['znesek'],
                'is_active' => $isActive,
                'ended_at' => $endedAt,
            ]);

            $this->persistRecordMigrationMeta('sponsorship', $entry, $record);
        }
    }

    /**
     * @throws Exception
     */
    protected function importCsv($url): bool|array
    {
        if (! $url) {
            throw new Exception('CSV URL empty');
        }

        $header = null;
        $data = [];

        if (($handle = fopen($url, 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                if (! $header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    protected function persistRecordMigrationMeta(string $entity, Cat|PersonData|Sponsorship $model, array $csvRecord): void
    {
        DB::table('db_migration_meta')->insert([
            'entity' => $entity,
            'new_id' => $model->id,
            'prev_id' => $csvRecord['id'],
            'prev_data' => json_encode($csvRecord),
        ]);
    }

    protected function parseNullableString(string $value): ?string
    {
        return $value === 'NULL' ? null : $value;
    }

    protected function parseNullableDate(string $value): ?string
    {
        $dateString = $this->parseNullableString($value);

        if (
            ! $dateString ||
            Carbon::parse($dateString)->year < 1 // When stored in the prev DB as 0000-00-00
        ) {
            return null;
        }

        return $dateString;
    }

    protected function parseBoolean(string $value): bool
    {
        return $value === '1';
    }
}
