@extends('layouts.admin')
@section('title', 'Sign in')

@section('content')
<form method="POST" action="{{ route('admin.login.store') }}" class="card w-full max-w-sm">
    @csrf
    <div class="mb-6 text-center">
        <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-violet-500 text-white"><i data-lucide="graduation-cap" class="h-7 w-7"></i></span>
        <h1 class="mt-3 font-display text-2xl font-semibold text-slate-800">Admin sign in</h1>
    </div>

    <label for="email" class="label">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="input">
    @error('email')<p class="mt-1 text-sm font-semibold text-rose-500">{{ $message }}</p>@enderror

    <label for="password" class="label mt-4">Password</label>
    <input id="password" type="password" name="password" required class="input">

    <label class="mt-4 flex items-center gap-2 text-sm font-semibold text-slate-500">
        <input type="checkbox" name="remember" class="h-4 w-4 rounded accent-violet-500"> Keep me signed in
    </label>

    <button class="btn-primary mt-5 w-full">Sign in</button>
</form>
@endsection
