<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Valider;

class ValiderSeeder extends Seeder
{
    public function run()
    {
        // Create 10 instances of Valider using the factory
        Valider::factory()->count(30)->create();
    }
}
