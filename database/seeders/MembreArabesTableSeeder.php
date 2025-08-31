<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembreArabe;

class MembreArabesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MembreArabe::factory()->count(10)->create();
    }
}
