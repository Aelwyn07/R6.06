<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Trimester;
use App\Models\Year;
use App\Models\Teaching;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrimesterTest extends TestCase
{
    use RefreshDatabase;

    public function test_trimester_creation()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\TrimesterSeeder::class
        ]);
        
        $trimester = Trimester::first();

        $this->assertInstanceOf(Trimester::class, $trimester);
        $this->assertEquals(1, $trimester->trimester_number);
        $this->assertInstanceOf(Year::class, $trimester->year);
    }

    public function test_trimester_relationships()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\TrimesterSeeder::class,
            \Database\Seeders\TeachingSeeder::class
        ]);
        
        $trimester = Trimester::with(['year', 'teachings'])->first();

        // Test de la relation avec Year
        $this->assertInstanceOf(Year::class, $trimester->year);
        
        // Test de la relation avec Teaching
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $trimester->teachings);
        $this->assertInstanceOf(Teaching::class, $trimester->teachings->first());
    }

    public function test_trimester_validation()
    {
        $this->seed([
            \Database\Seeders\YearSeeder::class
        ]);

        $year = Year::first();

        $this->assertDatabaseHas('trimesters', [
            'trimester_number' => 1,
            'year_id' => $year->id
        ]);

        // Test de création avec une année inexistante
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Trimester::create([
            'trimester_number' => 1,
            'year_id' => 999 // ID inexistant
        ]);
    }
}