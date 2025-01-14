<?php

namespace Database\Seeders;

use App\Models\Year;
use App\Models\Trimester;
use Illuminate\Database\Seeder;

class TrimesterSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier si l'année existe
        $year = Year::where('periodicity', 'Trimestrial')->first();
        
        if (!$year) {
            // Créer l'année si elle n'existe pas
            $year = Year::create([
                'name' => '2024-2025',
                'periodicity' => 'Trimestrial',
            ]);
        }

        // Créer les trimestres
        for ($i = 1; $i <= 3; $i++) {
            Trimester::create([
                'trimester_number' => $i,
                'year_id' => $year->id
            ]);
        }
    }
}
