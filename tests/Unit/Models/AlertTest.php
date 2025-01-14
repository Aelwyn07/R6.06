<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Alert;
use App\Models\Year;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_alert_creation()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\AlertSeeder::class
        ]);
        
        $alert = Alert::first();

        $this->assertInstanceOf(Alert::class, $alert);
        $this->assertEquals('Exemple de message d\'alerte', $alert->message);
        $this->assertInstanceOf(Year::class, $alert->year);
    }

    public function test_alert_relationships()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\YearSeeder::class,
            \Database\Seeders\AlertSeeder::class
        ]);
        
        $alert = Alert::with('year')->first();

        $this->assertInstanceOf(Year::class, $alert->year);
    }
}
