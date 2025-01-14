<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcademicPromotion;

class AcademicPromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AcademicPromotion::create([
            'name' => 'BUT1',
            'year_id' => 1,
        ]);
        AcademicPromotion::create([
            'name' => 'BUT2',
            'year_id' => 1,
        ]);
        AcademicPromotion::create([
            'name' => 'BUT3',
            'year_id' => 1,
        ]);
    }
}
