<?php

namespace Database\Factories;

use App\Models\Faculte;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaculteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Faculte::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->word,
            'ville' => $this->faker->city,
            'id_universite' => $this->faker->numberBetween(1, 10),
        ];
    }
}
