<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Year;
use App\Models\Semester;
use App\Models\Trimester;
use App\Models\Teaching;
use App\Models\Teacher;
use App\Models\AcademicPromotion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class YearTest extends TestCase
{
    use RefreshDatabase;

    public function test_year_creation()
    {
        $this->seed([
            \Database\Seeders\YearSeeder::class
        ]);
        
        $year = Year::first();

        $this->assertInstanceOf(Year::class, $year);
        $this->assertEquals('2024-2025', $year->name);
        $this->assertEquals('Semestrial', $year->periodicity);
    }

    public function test_year_relationships()
    {
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\SemesterSeeder::class,
            \Database\Seeders\TrimesterSeeder::class,
            \Database\Seeders\TeachingSeeder::class,
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\UserSeeder::class,
            \Database\Seeders\TeacherSeeder::class,
            \Database\Seeders\AcademicPromotionSeeder::class
        ]);
        
        // Test année semestrielle
        $semesterYear = Year::where('periodicity', 'Semestrial')->first();
        $this->assertNotNull($semesterYear->semesters);
        $this->assertInstanceOf(Semester::class, $semesterYear->semesters->first());
        $this->assertCount(0, $semesterYear->trimesters);

        // Test année trimestrielle
        $trimesterYear = Year::where('periodicity', 'Trimestrial')->first();
        $this->assertNotNull($trimesterYear->trimesters);
        $this->assertInstanceOf(Trimester::class, $trimesterYear->trimesters->first());
        $this->assertCount(0, $trimesterYear->semesters);

        // Test autres relations
        $year = Year::with(['teachings', 'teachers', 'academicPromotions'])->first();
        $this->assertNotNull($year->teachings);
        $this->assertInstanceOf(Teaching::class, $year->teachings->first());
        $this->assertNotNull($year->teachers);
        $this->assertInstanceOf(Teacher::class, $year->teachers->first());
        $this->assertNotNull($year->academicPromotions);
        $this->assertInstanceOf(AcademicPromotion::class, $year->academicPromotions->first());
    }

    public function test_year_validation()
    {
        // Test de création avec des données valides
        $year = Year::create([
            'name' => '2025-2026',
            'periodicity' => 'Semestrial'
        ]);

        $this->assertDatabaseHas('years', [
            'name' => '2025-2026',
            'periodicity' => 'Semestrial'
        ]);

        // Test de création avec une périodicité invalide
        $this->expectException(ValidationException::class);
        
        Year::create([
            'name' => '2026-2027',
            'periodicity' => 'Invalid'
        ]);
    }
} 