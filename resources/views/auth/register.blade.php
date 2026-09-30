@extends('layouts.app')
@section('title', 'Daftar')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="mb-6 text-center">
        <div class="text-5xl">🎒</div>
        <h1 class="mt-2 font-display text-3xl font-semibold text-slate-800">Daftar tryout</h1>
        <p class="text-slate-500">Student ID muncul setelah kamu mendaftar, dan dikirim ke email orang tua jika diisi.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="card">
        @csrf
        @include('partials.student-fields', ['student' => null])

        <button class="btn-primary w-full"><i data-lucide="user-plus" class="h-5 w-5"></i> Daftar sekarang</button>
        <p class="mt-4 text-center text-sm text-slate-500">
            Sudah punya Student ID? <a class="font-bold text-violet-600 hover:underline" href="{{ route('login') }}">Masuk</a>
        </p>
    </form>
</div>
@endsection
