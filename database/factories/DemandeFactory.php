<?php

namespace Database\Factories;

use App\Models\Demande;
use App\Models\These;
use Illuminate\Database\Eloquent\Factories\Factory;

class DemandeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Demande::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'foramtion' => $this->faker->randomElement(['MPA', 'RNES', 'MI']),
            'num' => $this->faker->numberBetween(1, 100),
            'date' => $this->faker->date(),
            'etat' => $this->faker->randomElement(['Refusée', 'Acceptée', 'En attente']),
            'id_these' => \App\Models\These::factory()->create()->id,
        
        
        ];
    }
}
