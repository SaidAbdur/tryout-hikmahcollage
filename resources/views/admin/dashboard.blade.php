@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="mb-7 flex flex-wrap items-end justify-between gap-3">
    <div><p class="text-sm font-bold uppercase tracking-widest text-blue-700">Hikmah College</p><h1 class="mt-1 font-display text-3xl font-semibold text-slate-800">Ringkasan Admin</h1></div>
    <a href="{{ route('admin.students.pending') }}" class="btn-primary">Tinjau pendaftaran</a>
</div>

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ([['Total siswa', $stats['students'], 'users', 'bg-blue-50 text-blue-700'], ['Menunggu persetujuan', $stats['pending'], 'user-round-check', 'bg-lime-100 text-lime-800'], ['Akun disetujui', $stats['approved'], 'badge-check', 'bg-emerald-50 text-emerald-700'], ['Mata pelajaran', $stats['subjects'], 'book-open', 'bg-slate-100 text-slate-700']] as [$label, $value, $icon, $tint])
        <div class="card rounded-lg border border-slate-200 shadow-sm">
            <div class="mb-4 grid h-10 w-10 place-items-center rounded-lg {{ $tint }}"><i data-lucide="{{ $icon }}" class="h-5 w-5"></i></div>
            <p class="font-display text-3xl font-semibold text-slate-800">{{ $value }}</p>
            <p class="mt-1 text-sm font-semibold text-slate-500">{{ $label }}</p>
        </div>
    @endforeach
</div>

<section class="mt-7 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
    <h2 class="font-display text-xl font-semibold text-slate-800">Operasional</h2>
    <div class="mt-4 grid gap-4 md:grid-cols-2">
        <a href="{{ route('admin.students.active') }}" class="flex items-center justify-between rounded-lg border border-slate-200 p-4 hover:border-blue-300">
            <span><strong class="block text-slate-800">Performa siswa aktif</strong><span class="text-sm text-slate-500">Lihat riwayat tryout dan skor siswa yang disetujui.</span></span>
            <span class="rounded-full bg-blue-50 px-3 py-1 font-black text-blue-800">{{ $stats['approved'] }}</span>
        </a>
        <a href="{{ route('admin.students.pending') }}" class="flex items-center justify-between rounded-lg border border-slate-200 p-4 hover:border-blue-300">
            <span><strong class="block text-slate-800">Pendaftaran perlu ditinjau</strong><span class="text-sm text-slate-500">Periksa data dan bukti siswa.</span></span>
            <span class="rounded-full bg-lime-100 px-3 py-1 font-black text-lime-800">{{ $stats['pending'] }}</span>
        </a>
        <a href="{{ route('admin.questions.index') }}" class="flex items-center justify-between rounded-lg border border-slate-200 p-4 hover:border-blue-300">
            <span><strong class="block text-slate-800">Bank soal</strong><span class="text-sm text-slate-500">{{ $stats['completed'] }} tryout selesai.</span></span>
            <i data-lucide="chevron-right" class="h-5 w-5 text-blue-700"></i>
        </a>
    </div>
</section>
@endsection
