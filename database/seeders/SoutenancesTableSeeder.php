<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Soutenance;

class SoutenancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Soutenance::factory()->count(10)->create();
    }
}
