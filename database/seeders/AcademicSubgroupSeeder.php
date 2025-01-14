<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcademicSubgroup;

class AcademicSubgroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AcademicSubgroup::create([
            'name' => 'G1A',
            'academic_group_id' => 1
        ]);
        AcademicSubgroup::create([
            'name' => 'G1B',
            'academic_group_id' => 1
        ]);
        AcademicSubgroup::create([
            'name' => 'G2A',
            'academic_group_id' => 2
        ]);
        AcademicSubgroup::create([
            'name' => 'G2B',
            'academic_group_id' => 2
        ]);
        AcademicSubgroup::create([
            'name' => 'G3A',
            'academic_group_id' => 3
        ]);
        AcademicSubgroup::create([
            'name' => 'G3B',
            'academic_group_id' => 3
        ]);
        AcademicSubgroup::create([
            'name' => 'G4A',
            'academic_group_id' => 4
        ]);
        AcademicSubgroup::create([
            'name' => 'G4B',
            'academic_group_id' => 4
        ]);
        AcademicSubgroup::create([
            'name' => 'G5A',
            'academic_group_id' => 5
        ]);
        AcademicSubgroup::create([
            'name' => 'G5B',
            'academic_group_id' => 5
        ]);
        AcademicSubgroup::create([
            'name' => 'G6A',
            'academic_group_id' => 6
        ]);
        AcademicSubgroup::create([
            'name' => 'G6B',
            'academic_group_id' => 6
        ]);
        AcademicSubgroup::create([
            'name' => 'G7A',
            'academic_group_id' => 7
        ]);
        AcademicSubgroup::create([
            'name' => 'G7B',
            'academic_group_id' => 7
        ]);
    }
}
