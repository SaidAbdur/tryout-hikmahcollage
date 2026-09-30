<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TryoutSession;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $completed = TryoutSession::where('status', 'completed');

        $stats = [
            'students' => Student::count(),
            'today' => Student::whereDate('created_at', today())->count(),
            'completed' => (clone $completed)->count(),
            'avg' => round((clone $completed)->avg('score') ?? 0, 1),
            'live' => TryoutSession::where('status', 'in_progress')->count(),
        ];

        $bySubject = (clone $completed)
            ->selectRaw('subject_id, avg(score) as avg_score')
            ->groupBy('subject_id')
            ->with('subject:id,name')
            ->get();

        $palette = ['#c4b5fd', '#7dd3fc', '#fcd34d', '#6ee7b7', '#fda4af'];
        $subjectChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $bySubject->map(fn ($r) => $r->subject->name)->values(),
                'datasets' => [[
                    'label' => 'Average score',
                    'data' => $bySubject->map(fn ($r) => round($r->avg_score, 1))->values(),
                    'backgroundColor' => $bySubject->keys()->map(fn ($i) => $palette[$i % count($palette)])->values(),
                    'borderRadius' => 12,
                ]],
            ],
            'options' => [
                'plugins' => ['legend' => ['display' => false]],
                'scales' => ['y' => ['beginAtZero' => true, 'max' => 100]],
            ],
        ];

        $leaders = Student::query()
            ->withAvg(['sessions as avg_score' => fn ($q) => $q->where('status', 'completed')], 'score')
            ->withCount(['sessions as attempts' => fn ($q) => $q->where('status', 'completed')])
            ->whereHas('sessions', fn ($q) => $q->where('status', 'completed'))
            ->orderByDesc('avg_score')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'subjectChart', 'leaders'));
    }

    /** Registration trend: ?period=daily (30 days) | weekly (12 weeks) | monthly (12 months). */
    public function registrations(Request $request): JsonResponse
    {
        $period = in_array($request->query('period'), ['weekly', 'monthly']) ? $request->query('period') : 'daily';

        [$from, $step, $format] = match ($period) {
            'weekly' => [now()->startOfWeek()->subWeeks(11), '1 week', 'd M'],
            'monthly' => [now()->startOfMonth()->subMonths(11), '1 month', 'M Y'],
            default => [today()->subDays(29), '1 day', 'd M'],
        };

        $bucket = fn (Carbon $d) => match ($period) {
            'weekly' => $d->copy()->startOfWeek()->toDateString(),
            'monthly' => $d->copy()->startOfMonth()->toDateString(),
            default => $d->toDateString(),
        };

        // Bucketed in PHP so the query stays identical on MySQL and PostgreSQL.
        $counts = Student::where('created_at', '>=', $from)->get(['id', 'created_at'])
            ->countBy(fn ($s) => $bucket($s->created_at));

        $labels = [];
        $data = [];
        foreach (CarbonPeriod::create($from, $step, now()) as $day) {
            $labels[] = $day->format($format);
            $data[] = $counts->get($bucket($day), 0);
        }

        return response()->json(compact('labels', 'data'));
    }
}
