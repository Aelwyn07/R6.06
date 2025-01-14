<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Year;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier si l'utilisateur existe
        $user = User::first();

        if (!$user) {
            // Si pas d'utilisateur, on lance d'abord le UserSeeder
            $this->call(UserSeeder::class);
            $user = User::first();
        }

        // Vérifier si l'année existe
        $year = Year::first();

        if (!$year) {
            // Si pas d'année, on lance d'abord le YearSeeder
            $this->call(YearSeeder::class);
            $year = Year::first();
        }

        // Créer l'enseignant
        Teacher::create([
            'user_id' => $user->id,
            'year_id' => $year->id,
            'acronym' => 'LD',
            'first_name' => 'Laurent',
            'last_name' => 'DUBREUIL',
        ]);
        Teacher::create([
            'user_id' => 2,
            'year_id' => 1,
            'acronym' => 'AP',
            'first_name' => 'Anais',
            'last_name' => 'POURSAT',
        ]);
        Teacher::create([
            'user_id' => 3,
            'year_id' => 1,
            'acronym' => 'CO',
            'first_name' => 'Cristina',
            'last_name' => 'ONETE',
        ]);
        Teacher::create([
            'user_id' => 2,
            'year_id' => 1,
            'acronym' => 'EJ',
            'first_name' => 'Elise',
            'last_name' => 'JOFFRE',
        ]);
        Teacher::create([
            'user_id' => 2,
            'year_id' => 1,
            'acronym' => 'SF',
            'first_name' => 'Said',
            'last_name' => 'FETTAHI',
        ]);
        Teacher::create([
            'user_id' => 2,
            'year_id' => 1,
            'acronym' => 'DM',
            'first_name' => 'David',
            'last_name' => 'MINGO',
        ]);
        Teacher::create([
            'user_id' => 2,
            'year_id' => 1,
            'acronym' => 'SM',
            'first_name' => 'Stephane',
            'last_name' => 'MERILLOU',
        ]);
        Teacher::create([
            'user_id' => 2,
            'year_id' => 1,
            'acronym' => 'NM',
            'first_name' => 'Nicolas',
            'last_name' => 'MERILLOU',
        ]);
        Teacher::create([
            'user_id' => 2,
            'year_id' => 1,
            'acronym' => 'VB',
            'first_name' => 'Véronique',
            'last_name' => 'BAULANT',
        ]);
        Teacher::create([
            'user_id' => 2,
            'year_id' => 1,
            'acronym' => 'PN',
            'first_name' => 'Pierre-Antoine',
            'last_name' => 'NAVARETTE',

        ]);
    }
}
