<?php

namespace Database\Seeders;

use App\Models\Week;
use Illuminate\Database\Seeder;

class WeekSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 16; $i++) {
            Week::create([
                'name' => 'S' . $i,
                'week_number' => $i,
                'year_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
