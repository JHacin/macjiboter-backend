<?php

namespace Database\Factories;

use App\Models\PersonData;
use App\Utilities\CountryList;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonDataFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = PersonData::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'gender' => array_rand(PersonData::GENDER_LABELS),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => $this->faker->date(),
            'address' => $this->faker->streetAddress,
            'zip_code' => $this->faker->postcode,
            'city' => $this->faker->city,
            'country' => array_rand(CountryList::COUNTRY_NAMES),
        ];
    }
}
