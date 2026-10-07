<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

/**
 * Sample content so every screen has something to show.
 * Replace these questions with the academic team's real bank.
 */
class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['Bahasa Indonesia', 'mandatory', 75],
            ['Matematika', 'mandatory', 75],
            ['Bahasa Inggris', 'mandatory', 75],
            ['Fisika', 'elective', 60],
            ['Kimia', 'elective', 60],
            ['Biologi', 'elective', 60],
            ['Ekonomi', 'elective', 50],
        ];

        foreach ($subjects as [$name, $type, $minutes]) {
            Subject::updateOrCreate(['name' => $name], ['type' => $type, 'duration_minutes' => $minutes]);
        }

    }
}
