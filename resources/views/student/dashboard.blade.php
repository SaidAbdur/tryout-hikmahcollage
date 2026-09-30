@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
<div class="card flex flex-col gap-6 bg-gradient-to-r from-violet-500 to-sky-400 text-white sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="font-display text-3xl font-semibold">Halo, {{ Str::before($student->name, ' ') }}! 👋</h1>
        <p class="mt-1 text-violet-50">Pilih mata pelajaran dan mulai tryout hari ini.</p>
        <span class="chip mt-3 bg-white/25 text-white">Student ID {{ $student->student_id }}</span>
    </div>
    <div class="grid grid-cols-3 gap-3 text-center">
        @foreach ([['Selesai', $stats['done']], ['Rata-rata', rtrim(rtrim(number_format($stats['avg'], 1), '0'), '.')], ['Terbaik', rtrim(rtrim(number_format($stats['best'], 1), '0'), '.')]] as [$label, $value])
            <div class="rounded-2xl bg-white/20 px-4 py-3">
                <p class="font-display text-2xl font-semibold">{{ $value }}</p>
                <p class="text-xs font-bold text-violet-50">{{ $label }}</p>
            </div>
        @endforeach
    </div>
</div>

@php
    $tints = ['bg-violet-100 text-violet-600', 'bg-sky-100 text-sky-600', 'bg-amber-100 text-amber-600', 'bg-emerald-100 text-emerald-600', 'bg-rose-100 text-rose-600'];
    $n = 0;
@endphp

@foreach (['mandatory' => 'Mata pelajaran wajib', 'elective' => 'Mata pelajaran pilihan'] as $type => $title)
    <h2 class="mb-4 mt-10 font-display text-2xl font-semibold text-slate-800">{{ $title }}</h2>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($subjects->get($type, collect()) as $subject)
            @php
                $act = $active->get($subject->id);
                $score = $best->get($subject->id);
                $tint = $tints[$n++ % count($tints)];
            @endphp
            <div class="card flex flex-col">
                <div class="mb-4 flex items-start justify-between gap-2">
                    <div class="grid h-12 w-12 place-items-center rounded-2xl {{ $tint }}"><i data-lucide="book-open" class="h-6 w-6"></i></div>

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

                <form method="POST" action="{{ route('tryout.start', $subject) }}" class="mt-auto pt-5">@csrf
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
            <p class="text-slate-400">Belum ada mata pelajaran di kategori ini.</p>
        @endforelse
    </div>
@endforeach
@endsection
