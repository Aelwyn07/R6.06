<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labels = [
            'Enseignants',
            'Enseignements',
            'Mode Enseignants',
            'Mode Enseignements',
            'Groupes',
            'Calendrier Prévisionnel',
            'Calendrier Prévisionnels',
            'Enseignants/Enseignements',
            'Mode Édition',
            'S',
            'CM',
            'TD',
            'TP',
            'M3C',
            'Initiale',
            'Continue',
            'Promotion',
            'Groupe',
            'Demi-groupe',
            'EDT',
            'Service',
            'Configurations',
            'Déconnexion',
            
        ];

        foreach ($labels as $label) {
            \App\Models\Label::create([
                'original_name' => $label,
            ]);
        }
    }
}
