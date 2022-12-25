<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\CatPhoto;
use App\Services\CatPhotoService;
use File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CatSeeder extends Seeder
{
    public function run()
    {
        $cats = Cat::factory()->count(100)->create();
        $cats->push(Cat::factory()->createOne(['is_group' => true]));
        $cats->push(Cat::factory()->createOne(['is_group' => true]));
        $cats->push(Cat::factory()->createOne(['is_group' => true]));

        $indices = [0, 1, 2, 3];

        /** @var Cat $cat */
        foreach ($cats as $cat) {
            $indicesShuffled = Arr::shuffle($indices);

            foreach ($indices as $index) {
                $filename = "fake_cat_photo_{$indicesShuffled[$index]}.jpg";

                CatPhoto::factory()->createOne([
                    'cat_id' => $cat->id,
                    'filename' => $filename,
                    'caption' => $cat->name . '-' . $index . '-naslov',
                    'index' => $index,
                    'sizes' => [
                        'md' => ['name' => 'md', 'width' => 960, 'filename' => $filename],
                        'sm' => ['name' => 'sm', 'width' => 480, 'filename' => $filename],
                    ],
                ]);
            }
        }

        $pathRoot = CatPhotoService::PATH_ROOT;
        $photosDirectory = storage_path("app/public/{$pathRoot}");

        // On fresh install, the root dir does not exist.
        if (!File::isDirectory($photosDirectory)) {
            File::makeDirectory($photosDirectory, 0777, true, true);
        }

        // If folder already exists, remove all previously saved photos.
        File::cleanDirectory($photosDirectory);

        foreach ($indices as $index) {
            File::copy(
                database_path("seeders/assets/fake_cat_photo_$index.jpg"),
                "{$photosDirectory}/fake_cat_photo_$index.jpg"
            );
        }
    }
}
