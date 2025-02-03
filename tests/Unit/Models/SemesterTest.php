<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Semester;
use App\Models\Year;
use App\Models\Teaching;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SemesterTest extends TestCase
{
    use RefreshDatabase;

    public function test_semester_creation()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\SemesterSeeder::class
        ]);
        
        $semester = Semester::first();

        $this->assertInstanceOf(Semester::class, $semester);
        $this->assertEquals(1, $semester->semester_number);
        $this->assertInstanceOf(Year::class, $semester->year);
    }

    public function test_semester_relationships()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\SemesterSeeder::class,
            \Database\Seeders\TeachingSeeder::class
        ]);
        
        $semester = Semester::with(['year', 'teachings'])->first();

        // Test de la relation avec Year
        $this->assertInstanceOf(Year::class, $semester->year);
        
        // Test de la relation avec Teaching
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $semester->teachings);
        $this->assertInstanceOf(Teaching::class, $semester->teachings->first());
    }

    public function test_semester_validation()
    {
        $this->seed([
            \Database\Seeders\YearSeeder::class
        ]);

        $year = Year::first();

        // Test de création avec des données valides
        $semester = Semester::create([
            'semester_number' => 1,
            'year_id' => $year->id
        ]);

        $this->assertDatabaseHas('semesters', [
            'semester_number' => 1,
            'year_id' => $year->id
        ]);

        // Test de création avec une année inexistante
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Semester::create([
            'semester_number' => 1,
            'year_id' => 999 // ID inexistant
        ]);
    }
}
