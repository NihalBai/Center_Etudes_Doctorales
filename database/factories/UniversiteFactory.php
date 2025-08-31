<?php

namespace Database\Factories;

use App\Models\Universite;
use Illuminate\Database\Eloquent\Factories\Factory;

class UniversiteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Universite::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->company,
        ];
    }
}
