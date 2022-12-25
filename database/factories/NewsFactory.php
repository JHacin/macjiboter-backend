<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = News::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->optional()->sentence,
            'body' => $this->faker->text,
        ];
    }
}
