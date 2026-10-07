@extends('layouts.admin')
@section('title', 'Performa siswa')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.students.active') }}" class="text-sm font-bold text-blue-700">← Siswa aktif</a>
    <div class="mt-2 flex flex-wrap items-end justify-between gap-3">
        <div><p class="text-sm font-bold uppercase tracking-widest text-blue-700">Riwayat tryout</p><h1 class="mt-1 font-display text-3xl font-semibold text-slate-800">{{ $student->name }}</h1><p class="mt-1 text-sm text-slate-500">{{ $student->student_id }} · {{ $student->school }}</p></div>
        <div class="rounded-lg border border-slate-200 bg-white px-5 py-3 text-right shadow-sm"><p class="text-xs font-bold uppercase text-slate-500">Rata-rata skor</p><p class="font-display text-2xl font-semibold text-blue-800">{{ number_format($averageScore, 1) }}</p></div>
    </div>
</div>

<section class="mb-6 grid gap-5 rounded-lg border border-slate-200 bg-white p-5 shadow-sm md:grid-cols-3">
    <div>
        <h2 class="mb-3 text-xs font-black uppercase tracking-widest text-blue-700">Data siswa</h2>
        <dl class="space-y-2 text-sm">
            <div><dt class="inline font-bold text-slate-500">Student ID:</dt> <dd class="inline text-slate-800">{{ $student->student_id }}</dd></div>
            <div><dt class="inline font-bold text-slate-500">Nama lengkap:</dt> <dd class="inline text-slate-800">{{ $student->name }}</dd></div>
            <div><dt class="inline font-bold text-slate-500">Nomor WhatsApp:</dt> <dd class="inline text-slate-800">{{ $student->phone }}</dd></div>
            <div><dt class="inline font-bold text-slate-500">Asal daerah:</dt> <dd class="inline text-slate-800">{{ $student->region }}</dd></div>
            <div><dt class="inline font-bold text-slate-500">Sekolah:</dt> <dd class="inline text-slate-800">{{ $student->school }}</dd></div>
            <div><dt class="inline font-bold text-slate-500">Jenjang Kelas:</dt> <dd class="inline text-slate-800">{{ $student->grade_level }}</dd></div>
            <div><dt class="inline font-bold text-slate-500">Tanggal lahir:</dt> <dd class="inline text-slate-800">{{ \Illuminate\Support\Carbon::parse($student->dob)->format('d M Y') }}</dd></div>
            <div><dt class="inline font-bold text-slate-500">Usia:</dt> <dd class="inline text-slate-800">{{ $student->age }} tahun</dd></div>
            <div><dt class="inline font-bold text-slate-500">Jenis kelamin:</dt> <dd class="inline text-slate-800">{{ $student->gender }}</dd></div>
        </dl>
    </div>
    <div>
        <h2 class="mb-3 text-xs font-black uppercase tracking-widest text-blue-700">Data orang tua</h2>
        <dl class="space-y-2 text-sm">
            <div><dt class="inline font-bold text-slate-500">Nama:</dt> <dd class="inline text-slate-800">{{ $student->parent_name ?: '-' }}</dd></div>
            <div><dt class="inline font-bold text-slate-500">Nomor WhatsApp:</dt> <dd class="inline text-slate-800">{{ $student->parent_phone ?: '-' }}</dd></div>
            <div><dt class="inline font-bold text-slate-500">Email:</dt> <dd class="inline break-all text-slate-800">{{ $student->parent_email ?: '-' }}</dd></div>
        </dl>
    </div>
    <div>
        <h2 class="mb-3 text-xs font-black uppercase tracking-widest text-blue-700">Akun</h2>
        <dl class="space-y-2 text-sm">
            <div><dt class="inline font-bold text-slate-500">Paket:</dt> <dd class="inline text-slate-800">{{ $student->package_type === 'free' ? 'Free' : 'Paid · Rp 29.000' }}</dd></div>
            <div><dt class="inline font-bold text-slate-500">Status:</dt> <dd class="inline font-bold text-emerald-700">{{ ucfirst($student->account_status) }}</dd></div>
        </dl>
    </div>
</section>

<section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-5 py-4"><h2 class="font-bold text-slate-800">Sesi selesai <span class="text-slate-400">({{ $sessions->count() }})</span></h2></div>
    <div class="divide-y divide-slate-100">
        @forelse ($sessions as $session)
            @php $score = max(0, min(100, (int) $session->score)); @endphp
            <article class="grid gap-3 px-5 py-4 md:grid-cols-[1fr_10rem_3rem] md:items-center">
                <div><h3 class="font-bold text-slate-800">{{ $session->subject->name }}</h3><p class="mt-1 text-xs font-semibold text-blue-700">{{ $session->tryoutSeries->name }}</p><p class="mt-1 text-xs text-slate-500">{{ $session->submitted_at?->format('d M Y, H:i') }}</p></div>
                <div class="h-2 overflow-hidden rounded-full bg-slate-100" role="img" aria-label="Skor {{ $score }} dari 100"><div class="h-full rounded-full {{ $score >= 75 ? 'bg-lime-500' : 'bg-blue-600' }}" style="width: {{ $score }}%"></div></div>
                <p class="text-right font-display text-xl font-semibold text-slate-800">{{ $score }}</p>
            </article>
        @empty
            <p class="px-5 py-12 text-center text-slate-500">Siswa ini belum menyelesaikan tryout.</p>
        @endforelse
    </div>
</section>
@endsection