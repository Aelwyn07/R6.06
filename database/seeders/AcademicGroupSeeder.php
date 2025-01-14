<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcademicGroup;

class AcademicGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AcademicGroup::create([
            'name' => 'G1',
            'academic_promotion_id' => 1
        ]);
        AcademicGroup::create([
            'name' => 'G2',
            'academic_promotion_id' => 1
        ]);
        AcademicGroup::create([
            'name' => 'G3',
            'academic_promotion_id' => 1
        ]);
        AcademicGroup::create([
            'name' => 'G4',
            'academic_promotion_id' => 2
        ]);
        AcademicGroup::create([
            'name' => 'G5',
            'academic_promotion_id' => 2
        ]);
        AcademicGroup::create([
            'name' => 'G6',
            'academic_promotion_id' => 3
        ]);
        AcademicGroup::create([
            'name' => 'G7',
            'academic_promotion_id' => 3
        ]);
    }
}
