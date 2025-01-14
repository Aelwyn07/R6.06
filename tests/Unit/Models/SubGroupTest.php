<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\AcademicGroup;
use App\Models\AcademicSubgroup;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubGroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_academic_subgroup_creation()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\AcademicPromotionSeeder::class,
            \Database\Seeders\AcademicGroupSeeder::class,
            \Database\Seeders\AcademicSubgroupSeeder::class
        ]);
        
        $subgroup = AcademicSubgroup::first();

        $this->assertInstanceOf(AcademicSubgroup::class, $subgroup);
        $this->assertEquals('G1A', $subgroup->name);
        $this->assertInstanceOf(AcademicGroup::class, $subgroup->academicGroup);
    }

    public function test_academic_subgroup_relationships()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\AcademicPromotionSeeder::class,
            \Database\Seeders\AcademicGroupSeeder::class,
            \Database\Seeders\AcademicSubgroupSeeder::class
        ]);
        
        $subgroup = AcademicSubgroup::with('academicGroup.academicPromotion')->first();

        // Test de la relation avec AcademicGroup
        $this->assertInstanceOf(AcademicGroup::class, $subgroup->academicGroup);
        $this->assertEquals('G1', $subgroup->academicGroup->name);

        // Test de la relation imbriquée avec AcademicPromotion
        $this->assertEquals('BUT1', $subgroup->academicGroup->academicPromotion->name);
    }

    public function test_academic_subgroup_validation()
    {
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\AcademicPromotionSeeder::class,
            \Database\Seeders\AcademicGroupSeeder::class
        ]);

        $group = AcademicGroup::first();

        // Test de création avec des données valides
        $subgroup = AcademicSubgroup::create([
            'name' => 'Test Subgroup',
            'academic_group_id' => $group->id
        ]);

        $this->assertDatabaseHas('academic_subgroups', [
            'name' => 'Test Subgroup',
            'academic_group_id' => $group->id
        ]);

        // Test de création avec un groupe inexistant
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        AcademicSubgroup::create([
            'name' => 'Invalid Subgroup',
            'academic_group_id' => 999 // ID inexistant
        ]);
    }
}
