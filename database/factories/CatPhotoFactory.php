<?php

namespace Database\Factories;

use App\Models\Cat;
use App\Models\CatPhoto;
use App\Services\CatPhotoService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class CatPhotoFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = CatPhoto::class;

    public function definition(): array
    {
        return [
            'cat_id' => Cat::inRandomOrder()->first() ?? Cat::factory(),
            'filename' => $this->faker->ean13() . '.jpg',
            'caption' => $this->faker->text,
            'index' => $this->faker->numberBetween(0, 3),
            'sizes' => [],
        ];
    }
}
