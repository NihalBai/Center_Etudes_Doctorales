<?php

namespace Database\Factories;

use App\Models\Membre;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Membre::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'sex' => $this->faker->randomElement(['male', 'female']),
            'tele' => $this->faker->phoneNumber,
            'id_faculte' => function () {
                return \App\Models\Faculte::factory()->create()->id;
            },
            'id_autre' => function () {
                return \App\Models\Autre::factory()->create()->id;
            },
        ];
    }
}
