<?php

namespace Database\Factories;

use App\Models\Effectuer;
use Illuminate\Database\Eloquent\Factories\Factory;

class EffectuerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Effectuer::class;
    protected $table = 'effectuer';

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'id_soutenance' => \App\Models\Soutenance::factory()->create()->id,
            'id_doctorant' => \App\Models\Doctorant::factory()->create()->id,
            'numero_ordre' => $this->faker->numberBetween(1, 100),
        ];
    }
}
