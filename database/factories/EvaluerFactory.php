<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Evaluer;

class EvaluerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Evaluer::class;
    protected $table = 'evaluer';

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_membre' => \App\Models\Membre::factory()->create()->id,
            'id_soutenance' => \App\Models\Soutenance::factory()->create()->id,
            'qualite' => $this->faker->randomElement(['Président', 'Président/rapporteur', 'Directeur de thèse', 'Examinateur', 'Rapporteur', 'Co_directeur']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
