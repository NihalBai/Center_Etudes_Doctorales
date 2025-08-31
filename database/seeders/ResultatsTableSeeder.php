<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resultat;

class ResultatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Resultat::factory()->count(10)->create();
    }
}
