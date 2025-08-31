<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\These;

class ThesesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        These::factory()->count(20)->create();
    }
}
