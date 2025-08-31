<?php

namespace Database\Factories;

use App\Models\MembreArabe;
use App\Models\Membre;

use Illuminate\Database\Eloquent\Factories\Factory;

class MembreArabeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MembreArabe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'membre_id' => function() {
                return Membre::inRandomOrder()->first()->id;
            },
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'qualite' => $this->faker->word,
            'grade' => $this->faker->word,
        ];
    }
}
