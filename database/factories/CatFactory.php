<?php

namespace Database\Factories;

use App\Models\Cat;
use App\Models\CatLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Cat::class;

    public function definition(): array
    {
        $numParagraphs = rand(2, 4);

        $randomStoryHtml = str_repeat("<p>{$this->faker->paragraph(rand(1, 8))}</p>", $numParagraphs);

        return [
            'name' => $this->faker->unique()->name,
            'gender' => array_rand(Cat::GENDER_LABELS),
            'status' => $this->faker->boolean(80) ? Cat::STATUS_SEEKING_SPONSORS : array_rand(Cat::STATUS_LABELS),
            'story_short' => $this->faker->text(config('validation.cat.story_short_maxlength')),
            'story' => $randomStoryHtml,
            'date_of_birth' => $this->faker->date(),
            'date_of_arrival_mh' => $this->faker->date(),
            'date_of_arrival_boter' => $this->faker->date(),
            'location_id' => CatLocation::factory(),
            'is_group' => $this->faker->boolean(5),
        ];
    }
}
