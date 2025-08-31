<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DoctorantArabe;

class DoctorantArabesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DoctorantArabe::factory()->count(10)->create();
    }
}
