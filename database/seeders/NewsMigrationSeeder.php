<?php

namespace Database\Seeders;

use App\Models\News;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Storage;

class NewsMigrationSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run(): void
    {
        $csvUrl = Storage::disk('s3')->url('migration/novice.csv');
        $records = array_reverse($this->importCsv($csvUrl));

        News::truncate();

        foreach ($records as $record) {
            $postedDate = Carbon::parse($record['date'])->toDateString();

            News::create([
                'body' => $record['text'],
                'created_at' => $postedDate,
                'updated_at' => $postedDate,
            ]);
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
}
