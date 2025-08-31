<?php

namespace Database\Factories;

use App\Models\Doctorant;
use App\Models\Membre;

use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Doctorant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'CINE' => $this->faker->unique()->numerify('CINE###'),
            'sex' => $this->faker->randomElement(['male', 'female']),
            'photo_path' => $this->faker->imageUrl(),
            'email' => $this->faker->unique()->safeEmail,
            'tele' => $this->faker->unique()->phoneNumber,
            'date_de_naissance' => $this->faker->date(),
            'lieu' => $this->faker->locale,
            'dossier' => $this->faker->word,
            'discipline'=> $this->faker->word,
            'id_encadrant' => \App\Models\Membre::factory()->create()->id,
            // 'id_encadrant' => function() {
            //     return Membre::inRandomOrder()->first()->id;
            // },
        
        ];
    }
}
