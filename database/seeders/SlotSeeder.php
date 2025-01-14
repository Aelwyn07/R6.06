<?php

namespace Database\Seeders;

use App\Models\Slot;
use Illuminate\Database\Seeder;

class SlotSeeder extends Seeder
{
    public function run(): void
    {
        // Semaine 1 - CM pour toute la promotion
        Slot::create([
            'duration' => 2,
            'teacher_id' => 1,
            'teaching_id' => 1,
            'academic_promotion_id' => 1,
            'week_id' => 1,
            'type' => 'CM',
            'substitute_teacher_id' => null  // Ajout du champ
        ]);

        // Semaine 2 - TD pour les groupes
        $groups = [1, 2]; // IDs des groupes
        foreach ($groups as $groupId) {
            Slot::create([
                'duration' => 1.5,
                'teacher_id' => 2,
                'teaching_id' => 1,
                'academic_promotion_id' => 1,
                'academic_group_id' => $groupId,
                'week_id' => 2,
                'type' => 'TD',
                'substitute_teacher_id' => null  // Ajout du champ
            ]);
        }

        // Semaine 2 - TP pour les sous-groupes
        $subgroups = [1, 2, 3, 4]; // IDs des sous-groupes
        foreach ($subgroups as $subgroupId) {
            Slot::create([
                'duration' => 1,
                'teacher_id' => 3,
                'teaching_id' => 1,
                'academic_promotion_id' => 1,
                'academic_group_id' => ceil($subgroupId/2), // Associe aux bons groupes
                'academic_subgroup_id' => $subgroupId,
                'week_id' => 2,
                'type' => 'TP',
                'substitute_teacher_id' => null  // Ajout du champ
            ]);
        }

        // Autre enseignement - Semaine 3
        Slot::create([
            'duration' => 2,
            'teacher_id' => 4,
            'teaching_id' => 2,
            'academic_promotion_id' => 1,
            'week_id' => 3,
            'type' => 'CM',
            'substitute_teacher_id' => null  // Ajout du champ
        ]);

        foreach ($groups as $groupId) {
            Slot::create([
                'duration' => 1.5,
                'teacher_id' => 4,
                'teaching_id' => 2,
                'academic_promotion_id' => 1,
                'academic_group_id' => $groupId,
                'week_id' => 3,
                'type' => 'TD',
                'substitute_teacher_id' => null  // Ajout du champ
            ]);
        }
    }
}
