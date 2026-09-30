<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TryoutSession;
use Illuminate\Http\JsonResponse;

class MonitoringController extends Controller
{
    public function index()
    {
        return view('admin.monitoring');
    }

    /** Polled every few seconds by the monitoring page. */
    public function data(): JsonResponse
    {
        // Close sessions whose time is up but whose student never pressed submit.
        TryoutSession::with('subject')->where('status', 'in_progress')->get()
            ->each(fn ($s) => $s->isExpired() && $s->finalize());

        $rows = TryoutSession::query()
            ->with([
                'student:id,student_id,name,class_grade',
                'subject' => fn ($q) => $q->withCount('questions'),
            ])
            ->withCount(['answers as answered' => fn ($q) => $q->whereNotNull('selected_option')])
            ->where(fn ($q) => $q->where('status', 'in_progress')->orWhereDate('started_at', today()))
            ->latest('started_at')
            ->limit(200)
            ->get()
            ->sortBy(fn ($s) => [$s->status === 'in_progress' ? 0 : 1, -$s->started_at->timestamp])
            ->values();

        return response()->json([
            'live' => $rows->where('status', 'in_progress')->count(),
            'rows' => $rows->map(fn ($s) => [
                'id' => $s->id,
                'url' => route('admin.students.show', $s->student_id),
                'student' => $s->student->name,
                'student_id' => $s->student->student_id,
                'grade' => $s->student->class_grade,
                'subject' => $s->subject->name,
                'status' => $s->status,
                'remaining' => $s->status === 'in_progress' ? $s->remainingSeconds() : 0,
                'answered' => $s->answered,
                'total' => $s->subject->questions_count,
                'score' => $s->score,
            ]),
        ]);
    }
}
