<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Universite;

class UniversitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Universite::factory()->count(10)->create();
    }
}
