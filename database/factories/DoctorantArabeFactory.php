<?php

namespace Database\Factories;

use App\Models\DoctorantArabe;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorantArabeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DoctorantArabe::class;

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
            'domaine' => $this->faker->word,
            'filiere' => $this->faker->word,
        ];
    }
}
