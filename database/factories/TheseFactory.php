<?php

namespace Database\Factories;

use App\Models\These;
use App\Models\Doctorant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TheseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = These::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'titreOriginal' => $this->faker->sentence,
            'titreFinal' => $this->faker->sentence,
            'acceptationDirecteur' => $this->faker->randomElement(['Oui', 'Non']),
            'formation' => $this->faker->word,
            'id_doctorant' => \App\Models\Doctorant::factory()->create()->id,
            // 'id_doctorant' => function() {
            //     return Doctorant::inRandomOrder()->first()->id;
            // },
        
        
        ];
    }
}
