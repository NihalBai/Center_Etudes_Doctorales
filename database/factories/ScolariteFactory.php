<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Doctorant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scolarite>
 */
class ScolariteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'niveau' => $this->faker->word,
            'specialite' => $this->faker->word,
            'mois' => $this->faker->monthName,
            'annee' => $this->faker->year,
            'mention' => $this->faker->word,
            'id_doctorant' => function() {
                return Doctorant::inRandomOrder()->first()->id;
            },

        ];
    }
}
