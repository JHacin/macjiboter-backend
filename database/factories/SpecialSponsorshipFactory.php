<?php

namespace Database\Factories;

use App\Models\PersonData;
use App\Models\SpecialSponsorship;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class SpecialSponsorshipFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = SpecialSponsorship::class;

    public function definition(): array
    {
        return [
            'type' => Arr::random(SpecialSponsorship::TYPES),
            'sponsor_id' => PersonData::factory(),
            'payer_id' => PersonData::factory(),
            'confirmed_at' => $this->faker->dateTimeBetween('-1 years', '-1 day'),
            'is_anonymous' => $this->faker->boolean(80),
            'is_gift' => false,
            'amount' => $this->faker->numberBetween(5, 100),
        ];
    }
}
