@extends('layouts.admin')
@section('title', $tryoutSeries->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.questions.index') }}" class="text-sm font-bold text-blue-700">← Semua series</a>
    <div class="mt-2 flex flex-wrap items-end justify-between gap-3">
        <div><p class="text-sm font-bold uppercase tracking-widest text-blue-700">{{ $tryoutSeries->type === 'wajib' ? 'Tryout wajib' : 'Tryout pilihan' }}</p><h1 class="mt-1 font-display text-3xl font-semibold text-slate-800">{{ $tryoutSeries->name }}</h1></div>
        <span class="chip {{ $tryoutSeries->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $tryoutSeries->status === 'active' ? 'Aktif' : 'Draft' }}</span>
    </div>
</div>

<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
    @forelse ($subjects as $subject)
        <a href="{{ route('admin.questions.subject', [$tryoutSeries, $subject]) }}" class="group rounded-lg border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
            <span class="grid h-12 w-12 place-items-center rounded-lg bg-blue-50 text-blue-700"><i data-lucide="book-open" class="h-6 w-6"></i></span>
            <h2 class="mt-5 font-display text-xl font-semibold text-slate-800 group-hover:text-blue-800">{{ $subject->name }}</h2>
            <p class="mt-2 text-sm font-bold text-slate-500">{{ $subject->questions_count }} soal di series ini</p>
        </a>
    @empty
        <p class="text-slate-500">Belum ada mata pelajaran dengan tipe yang sesuai untuk series ini.</p>
    @endforelse
</div>
@endsection