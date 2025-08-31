<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
            $this->call(UsersTableSeeder::class);
            // $this->call(UniversitesTableSeeder::class);
            // $this->call(FacultesTableSeeder::class);
            // $this->call(AutresTableSeeder::class);
            // $this->call(MembresTableSeeder::class);
            // $this->call(LocalisationsTableSeeder::class);
            // $this->call(ResultatsTableSeeder::class);
            // $this->call(SoutenancesTableSeeder::class);
            // $this->call(EffectuerTableSeeder::class);
            // $this->call(ThesesTableSeeder::class);
            // $this->call(DemandesTableSeeder::class);
            // $this->call(GradesTableSeeder::class);
            // $this->call(ScolaritesTableSeeder::class);
            // $this->call(GradeMembreTableSeeder::class);
            // $this->call(DoctorantsTableSeeder::class);
            // $this->call(EvaluerSeeder::class);
            // $this->call(ValiderSeeder::class);
            
            /* $this->call(DoctorantArabesTableSeeder::class);
            $this->call(MembreArabesTableSeeder::class); */

            /* User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]); */
    }
}
