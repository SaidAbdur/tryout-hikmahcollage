@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<h1 class="mb-6 font-display text-3xl font-semibold text-slate-800">Dashboard</h1>

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
    @foreach ([['Total students', $stats['students'], 'users', 'bg-violet-100 text-violet-600'],
               ['New today', $stats['today'], 'user-plus', 'bg-sky-100 text-sky-600'],
               ['Completed tryouts', $stats['completed'], 'circle-check', 'bg-emerald-100 text-emerald-600'],
               ['Average score', $stats['avg'], 'star', 'bg-amber-100 text-amber-600'],
               ['Taking a test now', $stats['live'], 'activity', 'bg-rose-100 text-rose-600']] as [$label, $value, $icon, $tint])
        <div class="card">
            <div class="mb-3 grid h-11 w-11 place-items-center rounded-2xl {{ $tint }}"><i data-lucide="{{ $icon }}" class="h-5 w-5"></i></div>
            <p class="font-display text-3xl font-semibold text-slate-800">{{ $value }}</p>
            <p class="text-sm font-semibold text-slate-500">{{ $label }}</p>
        </div>
    @endforeach
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-3">
    <div class="card xl:col-span-2" x-data="regChart('{{ route('admin.analytics.registrations') }}')">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
            <h2 class="font-display text-xl font-semibold text-slate-800">New student registrations</h2>
            <div class="flex gap-1">
                @foreach (['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly'] as $key => $label)
                    <button type="button" @click="set('{{ $key }}')"
                            :class="period === '{{ $key }}' ? 'bg-violet-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-violet-100'"
                            class="cursor-pointer rounded-xl px-3 py-1.5 text-sm font-bold transition">{{ $label }}</button>
                @endforeach
            </div>
        </div>
        <div class="relative h-72"><canvas x-ref="c"></canvas></div>
    </div>

    <div class="card" x-data="chartBox(@js($subjectChart))">
        <h2 class="mb-4 font-display text-xl font-semibold text-slate-800">Average score by subject</h2>
        <div class="relative h-72"><canvas x-ref="c"></canvas></div>
    </div>
</div>

<div class="card mt-6">
    <h2 class="mb-4 font-display text-xl font-semibold text-slate-800">Top students by average score</h2>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[30rem] text-left text-sm">
            <thead class="text-xs font-bold text-slate-400">
                <tr><th class="pb-3">Rank</th><th class="pb-3">Student</th><th class="pb-3">Class</th><th class="pb-3">Tryouts</th><th class="pb-3 text-right">Average</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($leaders as $i => $s)
                    <tr>
                        <td class="py-3 text-lg">{{ ['🥇', '🥈', '🥉'][$i] ?? $i + 1 }}</td>
                        <td class="font-bold text-slate-800"><a href="{{ route('admin.students.show', $s) }}" class="hover:text-violet-600">{{ $s->name }}</a>
                            <p class="text-xs font-semibold text-slate-400">{{ $s->student_id }}</p></td>
                        <td>{{ $s->class_grade }}</td>
                        <td>{{ $s->attempts }}</td>
                        <td class="text-right font-display text-lg font-semibold text-violet-600">{{ number_format($s->avg_score, 1) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-slate-400">No completed tryouts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
