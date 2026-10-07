@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
<div class="card flex flex-col gap-6 border-0 bg-gradient-to-br from-blue-800 via-blue-700 to-emerald-600 text-white shadow-lg sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="font-display text-3xl font-semibold">Halo, {{ Str::before($student->name, ' ') }}! 👋</h1>
        <p class="mt-1 text-blue-100">Pilih mata pelajaran dan mulai tryout hari ini.</p>
        <span class="chip mt-3 bg-lime-300 text-blue-950">Student ID {{ $student->student_id }}</span>
    </div>
    <div class="grid grid-cols-3 gap-3 text-center">
        @foreach ([['Selesai', $stats['done']], ['Rata-rata', rtrim(rtrim(number_format($stats['avg'], 1), '0'), '.')], ['Terbaik', rtrim(rtrim(number_format($stats['best'], 1), '0'), '.')]] as [$label, $value])
            <div class="rounded-lg bg-white/15 px-4 py-3">
                <p class="font-display text-2xl font-semibold">{{ $value }}</p>
                <p class="text-xs font-bold text-blue-100">{{ $label }}</p>
            </div>
        @endforeach
    </div>
</div>

@php
    $tints = ['bg-blue-50 text-blue-700', 'bg-lime-100 text-lime-800', 'bg-amber-100 text-amber-700', 'bg-emerald-100 text-emerald-700', 'bg-sky-100 text-sky-700'];
    $n = 0;
@endphp

@forelse ($series as $tryoutSeries)
    <section class="mt-10">
        <div class="mb-4 flex flex-wrap items-center gap-3">
            <h2 class="font-display text-2xl font-semibold text-slate-800">{{ $tryoutSeries->name }}</h2>
            <span class="chip bg-lime-100 text-lime-800">{{ $tryoutSeries->type === 'wajib' ? 'Wajib' : 'Pilihan' }}</span>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($seriesSubjects->get($tryoutSeries->id, collect()) as $subject)
            @php
                $sessionKey = $tryoutSeries->id.':'.$subject->id;
                $act = $active->get($sessionKey);
                $score = $best->get($sessionKey);
                $tint = $tints[$n++ % count($tints)];
            @endphp
            <div class="card flex flex-col">
                <div class="mb-4 flex items-start justify-between gap-2">
                    <div class="grid h-12 w-12 place-items-center rounded-lg {{ $tint }}"><i data-lucide="book-open" class="h-6 w-6"></i></div>

                    @if ($act)
                        <span class="chip bg-amber-100 text-amber-700">
                            <i data-lucide="timer" class="h-3.5 w-3.5"></i>
                            <span x-data="countdown({{ $act->remainingSeconds() }})" x-text="t"></span> tersisa
                        </span>
                    @elseif (! is_null($score))
                        <span class="chip bg-emerald-100 text-emerald-700">Skor terbaik {{ rtrim(rtrim(number_format($score, 1), '0'), '.') }}</span>
                    @else
                        <span class="chip bg-slate-100 text-slate-500">Belum dikerjakan</span>
                    @endif
                </div>

                <h3 class="font-display text-xl font-semibold text-slate-800">{{ $subject->name }}</h3>
                <div class="mt-2 flex flex-wrap gap-2">
                    <span class="chip bg-slate-100 text-slate-600"><i data-lucide="clock" class="h-3.5 w-3.5"></i> {{ $subject->duration_minutes }} menit</span>
                    <span class="chip bg-slate-100 text-slate-600">{{ $subject->questions_count }} soal</span>
                </div>

                <form method="POST" action="{{ route('tryout.start', [$tryoutSeries, $subject]) }}" class="mt-auto pt-5">@csrf
                    @if ($subject->questions_count === 0)
                        <button type="button" disabled class="btn-soft w-full opacity-60">Segera hadir</button>
                    @else
                        <button class="btn-primary w-full">
                            <i data-lucide="play" class="h-5 w-5"></i>
                            {{ $act ? 'Lanjutkan tryout' : (! is_null($score) ? 'Coba lagi' : 'Mulai tryout') }}
                        </button>
                    @endif
                </form>
            </div>
        @empty
            <p class="text-slate-400">Belum ada mata pelajaran di series ini.</p>
        @endforelse
        </div>
    </section>
@empty
    <div class="card mt-8 text-center text-slate-500">Belum ada tryout series yang aktif.</div>
@endforelse
@endsection
