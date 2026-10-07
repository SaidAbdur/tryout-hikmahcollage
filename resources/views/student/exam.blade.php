@extends('layouts.app')
@section('title', $session->subject->name)
@section('focus', '1')

@section('content')
<div x-data="exam({
        questions: @js($questions),
        answers: @js($answers),
        remaining: {{ $remaining }},
        answerUrl: '{{ route('tryout.answer', $session) }}',
        csrf: '{{ csrf_token() }}'
     })"
     @keydown.window.arrow-right="go(i + 1)" @keydown.window.arrow-left="go(i - 1)">

    {{-- Sticky bar: subject, timer, finish --}}
    <div class="sticky top-0 z-30 -mx-4 -mt-8 mb-6 border-b border-white/60 bg-white/85 px-4 py-3 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-xs font-bold text-violet-500">{{ $session->subject->type === 'mandatory' ? 'Mata pelajaran wajib' : 'Mata pelajaran pilihan' }}</p>
                <h1 class="truncate font-display text-xl font-semibold text-slate-800">{{ $session->subject->name }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <span class="chip text-base transition" role="timer" aria-live="off"
                      :class="remaining < 300 ? 'animate-pulse bg-rose-100 text-rose-600' : 'bg-violet-100 text-violet-700'">
                    <i data-lucide="timer" class="h-4 w-4"></i><span x-text="clock"></span>
                </span>
                <button type="button" @click="confirming = true" class="btn-primary"><i data-lucide="send" class="h-4 w-4"></i> Selesai</button>
            </div>
        </div>
    </div>

    <div class="mx-auto grid max-w-6xl gap-6 lg:grid-cols-[1fr_19rem]">
        {{-- Question --}}
        <section class="card">
            <div class="mb-5 flex items-center justify-between gap-2">
                <span class="chip bg-sky-100 text-sky-700" x-text="'Soal ' + (i + 1) + ' dari ' + qs.length"></span>
                <button type="button" @click="flag()" class="chip cursor-pointer transition"
                        :class="answers[q.id] && answers[q.id].f ? 'bg-amber-200 text-amber-800' : 'bg-slate-100 text-slate-500 hover:bg-amber-100'">
                    <i data-lucide="flag" class="h-4 w-4"></i> Ragu-ragu
                </button>
            </div>

            <p class="whitespace-pre-line text-lg font-semibold leading-relaxed text-slate-800" x-text="q.text"></p>

            <div class="mt-6 space-y-3" role="radiogroup">
                <template x-for="(text, key) in q.options" :key="q.id + key">
                        <label class="mb-3 block cursor-pointer rounded-xl border-2 p-4 transition hover:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100"
                           :class="answers[q.id] && answers[q.id].o === key ? 'border-blue-600 bg-blue-50 shadow-md shadow-blue-100' : 'border-slate-200 bg-white'">
                        <span class="flex items-start gap-3">
                            <input type="radio" class="mt-1 h-6 w-6 shrink-0 cursor-pointer accent-blue-600" :name="'q' + q.id" :checked="answers[q.id] && answers[q.id].o === key" @change="choose(key)">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl font-extrabold transition"
                                  :class="answers[q.id] && answers[q.id].o === key ? 'bg-blue-700 text-white' : 'bg-slate-100 text-slate-600'" x-text="key"></span>
                        <span class="whitespace-pre-line pt-1 font-semibold" x-text="text"></span>
                        </span>
                    </label>
                </template>
            </div>

            <div class="mt-8 flex justify-between gap-3">
                <button type="button" class="btn-soft" @click="go(i - 1)" :disabled="i === 0"><i data-lucide="chevron-left" class="h-5 w-5"></i> Sebelumnya</button>
                <template x-if="i < qs.length - 1">
                    <button type="button" class="btn-primary" @click="go(i + 1)">Berikutnya <i data-lucide="chevron-right" class="h-5 w-5"></i></button>
                </template>
                <template x-if="i === qs.length - 1">
                    <button type="button" class="btn-primary" @click="confirming = true"><i data-lucide="send" class="h-4 w-4"></i> Selesai</button>
                </template>
            </div>
        </section>

        {{-- Navigator --}}
        <aside class="card h-fit lg:sticky lg:top-24">
            <h2 class="font-display text-lg font-semibold text-slate-800">Navigasi soal</h2>
            <p class="mb-4 text-sm font-semibold text-slate-500"><span x-text="answered"></span> dari <span x-text="qs.length"></span> soal terjawab</p>

            <div class="grid grid-cols-5 gap-2">
                <template x-for="(item, n) in qs" :key="item.id">
                    <button type="button" @click="go(n)" x-text="n + 1"
                            class="grid h-10 cursor-pointer place-items-center rounded-xl text-sm font-extrabold transition hover:-translate-y-0.5"
                            :class="[
                                { answered: 'bg-blue-600 text-white', flagged: 'bg-amber-300 text-amber-900', empty: 'border border-slate-200 bg-white text-slate-500' }[state(item.id)],
                                n === i ? 'ring-4 ring-violet-300' : ''
                            ]"></button>
                </template>
            </div>

            <div class="mt-5 flex flex-wrap gap-2 text-xs font-bold">
                <span class="chip bg-blue-100 text-blue-700">Terjawab</span>
                <span class="chip bg-amber-100 text-amber-700">Ragu-ragu (<span x-text="flagged"></span>)</span>
                <span class="chip bg-slate-100 text-slate-500">Kosong</span>
            </div>
        </aside>
    </div>

    {{-- Confirm dialog --}}
    <div x-show="confirming" x-cloak x-transition.opacity class="fixed inset-0 z-50 grid place-items-center bg-slate-900/40 p-4">
        <div @click.outside="confirming = false" class="card w-full max-w-md text-center">
            <div class="text-5xl">🤔</div>
            <h2 class="mt-2 font-display text-2xl font-semibold text-slate-800">Kumpulkan jawaban?</h2>
            <p class="mt-2 text-slate-500">
                Kamu menjawab <b x-text="answered"></b> dari <b x-text="qs.length"></b> soal.
                <span x-show="qs.length - answered > 0">Masih ada <b x-text="qs.length - answered"></b> soal kosong.</span>
                Setelah dikumpulkan, jawaban tidak bisa diubah.
            </p>
            <div class="mt-6 grid grid-cols-2 gap-3">
                <button type="button" class="btn-soft" @click="confirming = false">Kembali mengerjakan</button>
                <button type="button" class="btn-primary" :disabled="submitting" @click="submit()">Ya, kumpulkan</button>
            </div>
        </div>
    </div>

    <form x-ref="form" method="POST" action="{{ route('tryout.submit', $session) }}" class="hidden">@csrf</form>
</div>
@endsection
