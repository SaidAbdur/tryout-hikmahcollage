<?php

namespace Database\Seeders;

use App\Models\Question;
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

        // [subject, question, A, B, C, D, E, correct, explanation]
        $bank = [
            ['Matematika', 'Jika 2x + 5 = 17, maka nilai x adalah ...', '4', '5', '6', '7', '8', 'C', "2x + 5 = 17\n2x = 12\nx = 6"],
            ['Matematika', 'Luas lingkaran dengan jari-jari 7 cm adalah ... (π = 22/7)', '44 cm²', '154 cm²', '308 cm²', '22 cm²', '176 cm²', 'B', 'L = π × r² = 22/7 × 7 × 7 = 154 cm².'],
            ['Matematika', 'Hasil dari 3/4 + 1/2 adalah ...', '4/6', '5/4', '3/8', '1', '2/3', 'B', 'Samakan penyebut: 3/4 + 2/4 = 5/4.'],
            ['Bahasa Indonesia', 'Kalimat yang menggunakan huruf kapital dengan tepat adalah ...', 'Kami berlibur ke Pulau bali.', 'Ibu membeli buah di Pasar Baru.', 'dia lahir di kota Surabaya.', 'Adik membaca buku Cerita Rakyat.', 'saya bersekolah di SMA Negeri 1.', 'B', 'Nama tempat ("Pasar Baru") ditulis dengan huruf kapital. Pilihan lain salah: "bali" seharusnya "Bali", kalimat tidak boleh diawali huruf kecil, dan "Cerita Rakyat" bukan judul buku yang disebut jelas.'],
            ['Bahasa Indonesia', 'Antonim kata "rajin" adalah ...', 'tekun', 'malas', 'giat', 'ulet', 'cermat', 'B', 'Antonim berarti lawan kata. Lawan "rajin" adalah "malas".'],
            ['Bahasa Inggris', 'She ___ to school every day.', 'go', 'goes', 'going', 'gone', 'went', 'B', 'Simple present dengan subjek orang ketiga tunggal (she) memakai verb + s/es, jadi "goes".'],
            ['Bahasa Inggris', 'The synonym of "happy" is ...', 'sad', 'angry', 'glad', 'tired', 'bored', 'C', '"Glad" memiliki arti yang sama dengan "happy".'],
            ['Fisika', 'Satuan SI untuk gaya adalah ...', 'Joule', 'Watt', 'Newton', 'Pascal', 'Kilogram', 'C', 'Gaya diukur dalam Newton (N). Joule untuk energi, Watt untuk daya, Pascal untuk tekanan.'],
            ['Fisika', 'Mobil bergerak dengan kecepatan konstan 20 m/s selama 10 detik. Jarak yang ditempuh adalah ...', '2 m', '20 m', '100 m', '200 m', '2000 m', 'D', 's = v × t = 20 × 10 = 200 m.'],
        ];

        foreach ($bank as [$subject, $text, $a, $b, $c, $d, $e, $correct, $explanation]) {
            Question::firstOrCreate(
                ['subject_id' => Subject::where('name', $subject)->value('id'), 'question_text' => $text],
                [
                    'option_a' => $a, 'option_b' => $b, 'option_c' => $c, 'option_d' => $d, 'option_e' => $e,
                    'correct_option' => $correct, 'explanation_text' => $explanation,
                ],
            );
        }
    }
}
