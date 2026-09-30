<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::query()
            ->filter($request->all())
            ->withCount(['sessions as attempts' => fn ($q) => $q->where('status', 'completed')])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $grades = Student::distinct()->orderBy('class_grade')->pluck('class_grade');

        return view('admin.students.index', compact('students', 'grades'));
    }

    public function show(Student $student)
    {
        $done = $student->sessions()
            ->where('status', 'completed')
            ->with('subject')
            ->orderBy('submitted_at')
            ->get();

        // Average per subject, weakest first.
        $bySubject = $done->groupBy(fn ($s) => $s->subject->name)
            ->map(fn ($g) => round($g->avg('score'), 1))
            ->sort();

        $weak = $bySubject->take(2);
        $strong = $bySubject->reverse()->take(2);

        $progressChart = [
            'type' => 'line',
            'data' => [
                'labels' => $done->map(fn ($s) => $s->submitted_at->format('d M').' '.$s->subject->name)->values(),
                'datasets' => [[
                    'label' => 'Score',
                    'data' => $done->pluck('score')->values(),
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139,92,246,.15)',
                    'fill' => true,
                    'tension' => 0.35,
                ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['y' => ['beginAtZero' => true, 'max' => 100]],
            ],
        ];

        $subjectChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $bySubject->keys()->values(),
                'datasets' => [[
                    'label' => 'Average',
                    'data' => $bySubject->values(),
                    'backgroundColor' => '#7dd3fc',
                    'borderRadius' => 12,
                ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['y' => ['beginAtZero' => true, 'max' => 100]],
            ],
        ];

        return view('admin.students.show', compact('student', 'done', 'weak', 'strong', 'progressChart', 'subjectChart'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $student->update($request->validate(Student::rules()));

        return redirect()->route('admin.students.index')->with('status', 'Student updated.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete(); // sessions and answers cascade

        return redirect()->route('admin.students.index')->with('status', 'Student deleted.');
    }

    public function export(Request $request)
    {
        return Excel::download(new StudentsExport($request->all()), 'students-'.now()->format('Ymd-His').'.xlsx');
    }
}
