<?php

namespace Database\Factories;

use App\Models\Resultat;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResultatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Resultat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'observation' => $this->faker->randomElement(['Valider', 'Rattraper']),
            'mention' => $this->faker->randomElement(['Passable', 'Honorable', 'Très Honorable', 'Très Honorable avec félicitations du jury']),
            'formationDoctorale' => $this->faker->word,
            'specialite' => $this->faker->word,
            'id_soutenance' => \App\Models\Soutenance::factory()->create()->id,
        ];
    }
}
