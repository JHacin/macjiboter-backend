<?php

namespace Database\Factories;

use App\Models\CatLocation;
use App\Utilities\CountryList;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatLocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CatLocation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name,
            'address' => $this->faker->streetAddress,
            'zip_code' => $this->faker->postcode,
            'city' => $this->faker->city,
            'country' => array_rand(CountryList::COUNTRY_NAMES),
        ];
    }
}
