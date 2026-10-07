<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;

class PerformanceController extends Controller
{
    public function index()
    {
        $students = Student::where('account_status', 'approved')
            ->withCount(['sessions as completed_sessions' => fn ($query) => $query->where('status', 'completed')])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.students.active', compact('students'));
    }

    public function show(Student $student)
    {
        abort_unless($student->account_status === 'approved', 404);

        $sessions = $student->sessions()
            ->where('status', 'completed')
            ->with(['subject', 'tryoutSeries'])
            ->latest('submitted_at')
            ->get();
        $averageScore = round($sessions->avg('score') ?? 0, 1);

        return view('admin.students.performance', compact('student', 'sessions', 'averageScore'));
    }

    public function export()
    {
        return response()->streamDownload(function () {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'Student ID', 'Nama Lengkap', 'Nomor WhatsApp', 'Asal Daerah', 'Asal Sekolah',
                'Tanggal Lahir', 'Usia', 'Jenis Kelamin', 'Nama Orang Tua', 'Nomor WA Orang Tua',
                'Email Orang Tua', 'Paket', 'Status Akun',
            ], ',', '"', '');

            foreach (Student::query()->orderBy('id')->cursor() as $student) {
                $row = [
                    $student->student_id,
                    $student->name,
                    $student->phone,
                    $student->region,
                    $student->school,
                    $student->dob,
                    $student->age,
                    $student->gender,
                    $student->parent_name,
                    $student->parent_phone,
                    $student->parent_email,
                    $student->package_type,
                    $student->account_status,
                ];

                fputcsv($output, array_map(fn ($value) => $this->safeCsvValue($value), $row), ',', '"', '');
            }

            fclose($output);
        }, 'data-siswa-'.now()->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function safeCsvValue(mixed $value): string
    {
        $value = (string) ($value ?? '');

        return preg_match('/^[\t\r ]*[=+\-@]/', $value)
            ? "'".$value
            : $value;
    }
}