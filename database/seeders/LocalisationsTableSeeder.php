<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Localisation;

class LocalisationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Localisation::factory()->count(4)->create();
    }
}
