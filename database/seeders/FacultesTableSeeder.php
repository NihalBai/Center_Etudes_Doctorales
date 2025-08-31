<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculte;

class FacultesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Faculte::factory()->count(10)->create();
    }
}
