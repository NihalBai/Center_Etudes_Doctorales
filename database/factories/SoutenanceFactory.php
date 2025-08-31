<?php

namespace Database\Factories;

use App\Models\Soutenance;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoutenanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Soutenance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->date(),
            'heure' => $this->faker->time(),
            'etat' => $this->faker->randomElement(['En cours', 'Terminée', 'Reportée']),
            'id_localisation' => \App\Models\Localisation::factory()->create()->id,
        ];
    }
}
