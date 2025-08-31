<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evaluer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\LOG;


class EvaluerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        // Use factory to create Evaluer instances
        $evaluers = Evaluer::factory()->count(10)->create();

        // Log information about each created Evaluer instance
        foreach ($evaluers as $evaluer) {
            Log::info('Evaluer created: ' . json_encode($evaluer->toArray()));
        }
        // Create 10 instances of Evaluer using the factory
        /* Evaluer::factory()->count(10)->create(); */
        // Log generated data
        /* $evaluers = Evaluer::factory()->count(10)->create();
        foreach ($evaluers as $evaluer) {
        Log::info('Evaluer created: ' . json_encode($evaluer->toArray()));
        }
        DB::table('effectuer')->insert(Evaluer::factory()->count(10)->make()->toArray()); */
        
    }
}
