<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Effectuer;
use Illuminate\Support\Facades\DB;


class EffectuerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Effectuer::factory()->count(15)->create(); */
        DB::table('effectuer')->insert(Effectuer::factory()->count(10)->make()->toArray());
    }
}
