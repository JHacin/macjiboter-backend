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
            'confirmed_at' => $this->faker->boolean(70)
                ? $this->faker->dateTimeBetween('-1 years', '-1 day')
                : null,
            'is_anonymous' => $this->faker->boolean(80),
            'is_gift' => $this->faker->boolean(),
            'amount' => $this->faker->numberBetween(5, 100),
            'gift_notes' => $this->faker->text(),
            'gift_message' => $this->faker->text(),
            'gift_requested_activation_date' => $this->faker->boolean(5) ? $this->faker->date() : null,
        ];
    }
}
