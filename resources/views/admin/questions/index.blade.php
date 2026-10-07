@extends('layouts.admin')
@section('title', 'Bank soal')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div><p class="text-sm font-bold uppercase tracking-widest text-blue-700">Konten tryout</p><h1 class="mt-1 font-display text-3xl font-semibold text-slate-800">Bank soal</h1><p class="mt-1 text-sm text-slate-500">Pilih series untuk mengelola mata pelajaran dan soal.</p></div>
    <a href="{{ route('admin.questions.series.create') }}" class="btn-primary"><i data-lucide="plus" class="h-5 w-5"></i> Buat Series Baru</a>
</div>

<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
    @forelse ($series as $tryoutSeries)
        <a href="{{ route('admin.questions.series', $tryoutSeries) }}" class="group rounded-lg border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <span class="grid h-12 w-12 place-items-center rounded-lg {{ $tryoutSeries->type === 'wajib' ? 'bg-blue-50 text-blue-700' : 'bg-lime-100 text-lime-800' }}"><i data-lucide="layers-3" class="h-6 w-6"></i></span>
                <span class="chip {{ $tryoutSeries->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $tryoutSeries->status === 'active' ? 'Aktif' : 'Draft' }}</span>
            </div>
            <h2 class="mt-5 font-display text-xl font-semibold text-slate-800 group-hover:text-blue-800">{{ $tryoutSeries->name }}</h2>
            <div class="mt-3 flex items-center justify-between text-sm">
                <span class="font-bold text-slate-500">{{ $tryoutSeries->type === 'wajib' ? 'Wajib' : 'Pilihan' }}</span>
                <span class="font-bold text-blue-700">{{ $tryoutSeries->questions_count }} soal <i data-lucide="chevron-right" class="inline h-4 w-4"></i></span>
            </div>
        </a>
    @empty
        <div class="rounded-lg border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500 sm:col-span-2 xl:col-span-3">Belum ada tryout series. Buat series pertama untuk mulai menyusun bank soal.</div>
    @endforelse
</div>
@endsection