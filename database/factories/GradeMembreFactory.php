<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\GradeMembre;
use App\Models\Membre;
use App\Models\Grade;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GradeMembre>
 */
class GradeMembreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'membre_id' => \App\Models\Membre::factory()->create()->id,
            'grade_id'=> \App\Models\Grade::factory()->create()->id,
            // 'membre_id' => function() {
            //     return Membre::inRandomOrder()->first()->id;
            // },
            // 'grade_id' => function() {
            //     return Grade::inRandomOrder()->first()->id;
            // },
        ];
    }
}
