@extends('layouts.app')
@section('title', 'Hasil '.$session->subject->name)

@section('content')
@php [$emoji, $label, $badgeClass] = $session->badge(); @endphp

{{-- Instant-result popup, shown once right after submitting --}}
@if (session('just_submitted') && $showReview)
    <div x-data="{ open: true }" x-show="open" x-cloak x-transition.opacity @keydown.escape.window="open = false"
         class="fixed inset-0 z-50 grid place-items-center bg-slate-900/50 p-4">
        <div class="card pop-in w-full max-w-md text-center">
            <h2 class="font-display text-2xl font-semibold text-slate-800">Tryout selesai!</h2>
            <p class="text-slate-500">{{ $session->subject->name }}</p>
            @include('student._summary')
            <button type="button" @click="open = false" class="btn-primary w-full"><i data-lucide="lightbulb" class="h-5 w-5"></i> Lihat pembahasan</button>
        </div>
    </div>
@endif

<div class="mx-auto max-w-3xl">
    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('dashboard') }}" class="btn-soft"><i data-lucide="chevron-left" class="h-5 w-5"></i> Beranda</a>
        <a href="{{ route('history') }}" class="btn-soft"><i data-lucide="history" class="h-5 w-5"></i> Riwayat</a>
    </div>

    <div class="card text-center">
        <h1 class="font-display text-3xl font-semibold text-slate-800">{{ $session->subject->name }}</h1>
        <p class="text-sm font-semibold text-slate-500">Dikumpulkan {{ $session->submitted_at->format('d M Y, H:i') }}</p>
        @include('student._summary')
    </div>

    @if (! $showReview)
        <section class="mt-6 rounded-xl border border-lime-300 bg-gradient-to-r from-blue-800 to-blue-700 p-6 text-white shadow-lg md:p-8">
            <p class="text-sm font-black uppercase tracking-widest text-lime-300">Tingkatkan pengalaman belajarmu</p>
            <h2 class="mt-2 text-xl font-extrabold md:text-2xl">Upgrade ke Paket Paid (Rp 29.000) untuk melihat detail jawaban salah/benar dan pembahasan lengkap!</h2>
        </section>
    @else
        <h2 class="mb-4 mt-10 font-display text-2xl font-semibold text-slate-800">Pembahasan soal</h2>

        @foreach ($questions as $n => $q)
            @php
                $picked = optional($answers->get($q->id))->answered_option;
                $ok = $picked !== null && $picked === $q->correct_option;
            @endphp
            <article class="card mb-4">
                <div class="mb-3 flex items-center justify-between">
                    <span class="chip bg-slate-100 text-slate-600">Soal {{ $n + 1 }}</span>
                    @if ($picked === null)
                        <span class="chip bg-slate-100 text-slate-500"><i data-lucide="circle-minus" class="h-4 w-4"></i> Kosong</span>
                    @elseif ($ok)
                        <span class="chip bg-emerald-100 text-emerald-700"><i data-lucide="circle-check" class="h-4 w-4"></i> Benar</span>
                    @else
                        <span class="chip bg-rose-100 text-rose-600"><i data-lucide="circle-x" class="h-4 w-4"></i> Salah</span>
                    @endif
                </div>

                <p class="whitespace-pre-line font-semibold text-slate-800">{{ $q->question_text }}</p>

                <ul class="mt-4 space-y-2">
                    @foreach ($q->options() as $key => $text)
                        @php
                            $cls = $key === $q->correct_option ? 'border-emerald-300 bg-emerald-50'
                                : ($key === $picked ? 'border-rose-300 bg-rose-50' : 'border-slate-200');
                        @endphp
                        <li class="flex items-start gap-3 rounded-2xl border-2 p-3 {{ $cls }}">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-white font-extrabold">{{ $key }}</span>
                            <span class="flex-1 whitespace-pre-line pt-0.5 font-semibold">{{ $text }}</span>
                            @if ($key === $q->correct_option)
                                <span class="chip bg-emerald-200 text-emerald-800">Jawaban benar</span>
                            @elseif ($key === $picked)
                                <span class="chip bg-rose-200 text-rose-700">Jawabanmu</span>
                            @endif
                        </li>
                    @endforeach
                </ul>

                @if ($q->explanation_text)
                    <div class="mt-4 rounded-2xl bg-amber-50 p-4">
                        <p class="mb-1 flex items-center gap-2 font-extrabold text-amber-700"><i data-lucide="lightbulb" class="h-5 w-5"></i> Pembahasan</p>
                        <p class="whitespace-pre-line text-sm font-semibold text-slate-600">{{ $q->explanation_text }}</p>
                    </div>
                @endif
            </article>
        @endforeach
    @endif
</div>
@endsection
