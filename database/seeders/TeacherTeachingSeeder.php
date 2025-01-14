<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\Teaching;
use Illuminate\Database\Seeder;

class TeacherTeachingSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = Teacher::where('acronym', 'LD')->first();
        $teaching = Teaching::where('apogee_code', 'WEB_R1.05')->first();

        if ($teacher && $teaching) {
            $teacher->teachings()->attach($teaching->id);
        } else {
            // Log an error or handle the case where either $teacher or $teaching is null
            if (!$teacher) {
                echo "Teacher with acronym 'LD' not found.\n";
            }
            if (!$teaching) {
                echo "Teaching with apogee_code 'WEB_R1.05' not found.\n";
            }
        }
    }
}
