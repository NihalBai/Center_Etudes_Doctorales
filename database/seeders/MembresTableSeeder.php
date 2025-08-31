<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Membre;

class MembresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Membre::factory()->count(10)->create();
    }
}
