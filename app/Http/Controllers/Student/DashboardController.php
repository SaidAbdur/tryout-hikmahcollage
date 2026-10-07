<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\TryoutSeries;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        abort_unless($student->account_status === 'approved', 403);

        $sessions = $student->sessions()->with(['subject', 'tryoutSeries'])->get();
        // Close sessions whose timer ran out while the tab was closed.
        $sessions->each(fn ($s) => $s->isExpired() && $s->finalize());

        $sessionKey = fn ($session) => $session->tryout_series_id.':'.$session->subject_id;
        $active = $sessions->where('status', 'in_progress')->keyBy($sessionKey);
        $done = $sessions->where('status', 'completed');
        $best = $done->groupBy($sessionKey)->map(fn ($group) => $group->max('score'));

        $series = TryoutSeries::where('status', 'active')->orderBy('id')->get();
        $seriesSubjects = $series->mapWithKeys(function (TryoutSeries $tryoutSeries) {
            $subjectType = $tryoutSeries->type === 'wajib' ? 'mandatory' : 'elective';
            $subjects = Subject::where('type', $subjectType)
                ->withCount(['questions' => fn ($query) => $query->where('tryout_series_id', $tryoutSeries->id)])
                ->orderBy('name')
                ->get();

            return [$tryoutSeries->id => $subjects];
        });

        $stats = [
            'done' => $done->count(),
            'avg' => round($done->avg('score') ?? 0, 1),
            'best' => $done->max('score') ?? 0,
        ];

        return view('student.dashboard', compact('student', 'series', 'seriesSubjects', 'active', 'best', 'stats'));
    }

    public function history()
    {
        $student = Auth::guard('student')->user();
        abort_unless($student->account_status === 'approved', 403);

        $sessions = $student->sessions()
            ->where('status', 'completed')
            ->with(['subject', 'tryoutSeries'])
            ->latest('submitted_at')
            ->paginate(10);

        return view('student.history', compact('sessions'));
    }
}
