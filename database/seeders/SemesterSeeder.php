<?php

namespace Database\Seeders;

use App\Models\Year;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier si l'année semestrielle existe
        $year = Year::where('periodicity', 'Semestrial')->first();
        
        if (!$year) {
            // Créer l'année si elle n'existe pas
            $year = Year::create([
                'name' => '2024-2025',
                'periodicity' => 'Semestrial',
            ]);
        }

        // Créer les semestres
        for ($i = 1; $i <= 6; $i++) {
            Semester::create([
                'semester_number' => $i,
                'year_id' => $year->id
            ]);
        }
    }
}
