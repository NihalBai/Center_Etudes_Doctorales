<?php

namespace Database\Seeders;
use App\Models\Scolarite;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScolaritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Scolarite::factory()->count(10)->create();
    }
}
