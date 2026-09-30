@extends('layouts.app')
@section('title', 'Riwayat')

@section('content')
<h1 class="mb-6 font-display text-3xl font-semibold text-slate-800">Riwayat tryout</h1>

@forelse ($sessions as $s)
    @php [$emoji, $label, $badgeClass] = $s->badge(); @endphp
    <div class="card mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-display text-xl font-semibold text-slate-800">{{ $s->subject->name }}</h2>
            <p class="text-sm font-semibold text-slate-500">{{ $s->submitted_at->format('d M Y, H:i') }}</p>
            <div class="mt-2 flex flex-wrap gap-2">
                <span class="chip {{ $badgeClass }}">{{ $emoji }} {{ $label }}</span>
                <span class="chip bg-emerald-100 text-emerald-700">{{ $s->total_correct }} benar</span>
                <span class="chip bg-rose-100 text-rose-600">{{ $s->total_wrong }} salah</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <p class="font-display text-4xl font-semibold text-violet-600">{{ rtrim(rtrim(number_format($s->score, 1), '0'), '.') }}</p>
            <a href="{{ route('tryout.result', $s) }}" class="btn-soft"><i data-lucide="eye" class="h-5 w-5"></i> Lihat pembahasan</a>
        </div>
    </div>
@empty
    <div class="card py-12 text-center">
        <div class="text-5xl">🌱</div>
        <p class="mt-2 font-display text-xl font-semibold text-slate-800">Belum ada riwayat</p>
        <p class="text-slate-500">Selesaikan tryout pertamamu, hasilnya akan muncul di sini.</p>
        <a href="{{ route('dashboard') }}" class="btn-primary mt-5">Pilih tryout</a>
    </div>
@endforelse

{{ $sessions->links() }}
@endsection
