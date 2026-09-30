@extends('layouts.app')
@section('title', 'Masuk')

@section('content')
<div class="mx-auto max-w-md">
    @if (session('registered'))
        <div class="pop-in mb-6 rounded-3xl bg-emerald-100 p-5 text-center" x-data="{ copied: false }">
            <p class="font-bold text-emerald-700">Pendaftaran berhasil! Simpan Student ID kamu:</p>
            <button type="button" @click="navigator.clipboard.writeText('{{ session('registered') }}'); copied = true"
                    class="mt-3 cursor-pointer rounded-2xl bg-white px-5 py-3 font-display text-3xl font-semibold tracking-wider text-emerald-700 shadow">
                {{ session('registered') }}
            </button>
            <p class="mt-2 text-xs font-semibold text-emerald-600" x-text="copied ? 'Tersalin' : 'Ketuk untuk menyalin'"></p>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="card">
        @csrf
        <div class="mb-6 text-center">
            <div class="text-5xl">👋</div>
            <h1 class="mt-2 font-display text-3xl font-semibold text-slate-800">Masuk</h1>
            <p class="text-slate-500">Cukup masukkan Student ID, tanpa password.</p>
        </div>

        <label for="student_id" class="label">Student ID</label>
        <input id="student_id" name="student_id" value="{{ old('student_id', session('registered')) }}" required autofocus
               autocomplete="off" placeholder="STU-2026-AB12" class="input text-center font-display text-xl uppercase tracking-wider">
        @error('student_id')<p class="mt-2 text-sm font-semibold text-rose-500">{{ $message }}</p>@enderror

        <button class="btn-primary mt-5 w-full"><i data-lucide="log-in" class="h-5 w-5"></i> Masuk</button>
        <p class="mt-4 text-center text-sm text-slate-500">
            Belum punya Student ID? <a class="font-bold text-violet-600 hover:underline" href="{{ route('register') }}">Daftar dulu</a>
        </p>
    </form>
</div>
@endsection
