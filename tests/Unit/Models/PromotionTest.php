<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\AcademicPromotion;
use App\Models\AcademicGroup;
use App\Models\Year;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PromotionTest extends TestCase
{
    use RefreshDatabase;

    public function test_academic_promotion_creation()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\AcademicPromotionSeeder::class
        ]);
        
        $promotion = AcademicPromotion::first();

        $this->assertInstanceOf(AcademicPromotion::class, $promotion);
        $this->assertEquals('BUT1', $promotion->name);
        $this->assertInstanceOf(Year::class, $promotion->year);
    }

    public function test_academic_promotion_relationships()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\AcademicPromotionSeeder::class,
            \Database\Seeders\AcademicGroupSeeder::class
        ]);
        
        $promotion = AcademicPromotion::with('academicGroups')->first();

        // Test de la relation avec AcademicGroups
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $promotion->academicGroups);
        $this->assertInstanceOf(AcademicGroup::class, $promotion->academicGroups->first());
        $this->assertCount(3, $promotion->academicGroups); // Car BUT1 a trois groupes (G1, G2, G3)
    }

    public function test_academic_promotion_cascade_deletion()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\AcademicPromotionSeeder::class,
            \Database\Seeders\AcademicGroupSeeder::class,
            \Database\Seeders\AcademicSubgroupSeeder::class
        ]);

        $promotion = AcademicPromotion::first();
        $groupIds = $promotion->academicGroups->pluck('id')->toArray();

        // Supprimer la promotion
        $promotion->delete();

        // Vérifier que la promotion a été supprimée
        $this->assertDatabaseMissing('academic_promotions', [
            'id' => $promotion->id
        ]);

        // Vérifier que les groupes ont été supprimés en cascade
        foreach ($groupIds as $groupId) {
            $this->assertDatabaseMissing('academic_groups', [
                'id' => $groupId
            ]);
        }
    }

    public function test_academic_promotion_validation()
    {
        $this->seed([
            \Database\Seeders\YearSeeder::class
        ]);

        $year = Year::first();

        $this->assertDatabaseHas('academic_promotions', [
            'name' => 'Test Promotion',
            'year_id' => $year->id
        ]);

        // Test de création avec une année inexistante
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        AcademicPromotion::create([
            'name' => 'Invalid Promotion',
            'year_id' => 999 // ID inexistant
        ]);
    }
}
