<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Week;
use App\Models\Year;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeekTest extends TestCase
{
    use RefreshDatabase;

    public function test_week_belongs_to_year()
    {
        $year = new Year();
        $year->name = '2024-2025';
        $year->periodicity = 'Semestrial';
        $year->save();

        $week = new Week();
        $week->name = 'S1';
        $week->week_number = 1;
        $week->year_id = $year->id;
        $week->save();

        $this->assertInstanceOf(Year::class, $week->year);
        $this->assertEquals($year->id, $week->year->id);
    }

    public function test_week_number_must_be_between_1_and_52()
    {
        $year = new Year();
        $year->name = '2024-2025';
        $year->periodicity = 'Semestrial';
        $year->save();

        $week = new Week();
        $week->name = 'S1';
        $week->week_number = 1;
        $week->year_id = $year->id;
        $week->save();

        $this->assertEquals(1, $week->week_number);

        $week2 = new Week();
        $week2->name = 'S52';
        $week2->week_number = 52;
        $week2->year_id = $year->id;
        $week2->save();

        $this->assertEquals(52, $week2->week_number);
    }
}