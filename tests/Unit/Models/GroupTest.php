<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\AcademicGroup;
use App\Models\AcademicPromotion;
use App\Models\AcademicSubgroup;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_academic_group_creation()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\AcademicPromotionSeeder::class,
            \Database\Seeders\AcademicGroupSeeder::class
        ]);
        
        $group = AcademicGroup::first();

        $this->assertInstanceOf(AcademicGroup::class, $group);
        $this->assertEquals('G1', $group->name);
        $this->assertInstanceOf(AcademicPromotion::class, $group->academicPromotion);
    }

    public function test_academic_group_relationships()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\AcademicPromotionSeeder::class,
            \Database\Seeders\AcademicGroupSeeder::class,
            \Database\Seeders\AcademicSubgroupSeeder::class
        ]);
        
        $group = AcademicGroup::with(['academicPromotion', 'academicSubgroups'])->first();

        // Test de la relation avec AcademicPromotion
        $this->assertInstanceOf(AcademicPromotion::class, $group->academicPromotion);
        $this->assertEquals('BUT1', $group->academicPromotion->name);

        // Test de la relation avec AcademicSubgroups
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $group->academicSubgroups);
        $this->assertInstanceOf(AcademicSubgroup::class, $group->academicSubgroups->first());
        $this->assertCount(2, $group->academicSubgroups); // Car G1 a deux sous-groupes (G1A et G1B)
    }

    public function test_academic_group_cascade_deletion()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\AcademicPromotionSeeder::class,
            \Database\Seeders\AcademicGroupSeeder::class,
            \Database\Seeders\AcademicSubgroupSeeder::class
        ]);

        $group = AcademicGroup::first();
        $subgroupIds = $group->academicSubgroups->pluck('id')->toArray();

        // Supprimer le groupe
        $group->delete();

        // Vérifier que le groupe a été supprimé
        $this->assertDatabaseMissing('academic_groups', [
            'id' => $group->id
        ]);

        // Vérifier que les sous-groupes ont été supprimés en cascade
        foreach ($subgroupIds as $subgroupId) {
            $this->assertDatabaseMissing('academic_subgroups', [
                'id' => $subgroupId
            ]);
        }
    }

    public function test_academic_group_validation()
    {
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\AcademicPromotionSeeder::class
        ]);

        $promotion = AcademicPromotion::first();

        // Test de création avec des données valides
        $group = AcademicGroup::create([
            'name' => 'Test Group',
            'academic_promotion_id' => $promotion->id
        ]);

        $this->assertDatabaseHas('academic_groups', [
            'name' => 'Test Group',
            'academic_promotion_id' => $promotion->id
        ]);

        // Test de création avec une promotion inexistante
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        AcademicGroup::create([
            'name' => 'Invalid Group',
            'academic_promotion_id' => 999 // ID inexistant
        ]);
    }
}
