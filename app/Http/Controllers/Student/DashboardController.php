<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        $sessions = $student->sessions()->with('subject')->get();
        // Close sessions whose timer ran out while the tab was closed.
        $sessions->each(fn ($s) => $s->isExpired() && $s->finalize());

        $active = $sessions->where('status', 'in_progress')->keyBy('subject_id');
        $done = $sessions->where('status', 'completed');
        $best = $done->groupBy('subject_id')->map(fn ($g) => $g->max('score'));

        $subjects = Subject::withCount('questions')->orderBy('name')->get()->groupBy('type');

        $stats = [
            'done' => $done->count(),
            'avg' => round($done->avg('score') ?? 0, 1),
            'best' => $done->max('score') ?? 0,
        ];

        return view('student.dashboard', compact('student', 'subjects', 'active', 'best', 'stats'));
    }

    public function history()
    {
        $sessions = Auth::guard('student')->user()->sessions()
            ->where('status', 'completed')
            ->with('subject')
            ->latest('submitted_at')
            ->paginate(10);

        return view('student.history', compact('sessions'));
    }
}
