<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Subject;
use App\Models\TryoutSeries;
use Illuminate\Database\Seeder;

class TryoutSeriesSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = Subject::where('type', 'mandatory')->orderBy('name')->get();

        foreach (range(1, 3) as $seriesNumber) {
            $series = TryoutSeries::updateOrCreate(
                ['name' => "Tryout Wajib Series {$seriesNumber}"],
                ['type' => 'wajib', 'status' => 'active'],
            );

            foreach ($subjects as $subject) {
                foreach (range(1, 5) as $questionNumber) {
                    $questionText = "Soal latihan {$questionNumber} untuk {$subject->name} - {$series->name}: pilih jawaban yang tepat.";
                    $correctOption = ['A', 'B', 'C', 'D', 'E'][$questionNumber - 1];

                    Question::updateOrCreate(
                        [
                            'tryout_series_id' => $series->id,
                            'subject_id' => $subject->id,
                            'question_text' => $questionText,
                        ],
                        [
                            'option_a' => "Pilihan A untuk soal {$questionNumber}",
                            'option_b' => "Pilihan B untuk soal {$questionNumber}",
                            'option_c' => "Pilihan C untuk soal {$questionNumber}",
                            'option_d' => "Pilihan D untuk soal {$questionNumber}",
                            'option_e' => "Pilihan E untuk soal {$questionNumber}",
                            'correct_option' => $correctOption,
                            'explanation_text' => "Pembahasan contoh untuk soal {$questionNumber} mata pelajaran {$subject->name} di {$series->name}.",
                        ],
                    );
                }
            }
        }
    }
}