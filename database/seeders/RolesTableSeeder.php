<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Admin', 'type' => 'admin']);
        Role::create(['name' => 'Service CED', 'type' => 'service_ced']);
        Role::create(['name' => 'Directeur', 'type' => 'directeur']);
        Role::create(['name' => 'Rapporteur', 'type' => 'rapporteur']);
    }
}
