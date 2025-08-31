<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Models\\Valider>
 */
use App\Models\Valider;

class ValiderFactory extends Factory
{
    public function definition()
    {
        return [
            'id_membre' => rand(1, 25), // Adjust range based on your members
            'id_these' => rand(1, 25), // Adjust range based on your theses
            'avis' => $this->faker->randomElement(['accepté', 'refusé']),
            'lien_rapport' => $this->faker->url,
            'id_utilisateur' => rand(1, 10), // Adjust range based on your users
            'status' => $this->faker->randomElement(['Actif', 'Inactif']),
        ];
    }
}
