@extends('layouts.admin')
@section('title', 'Buat tryout series')

@section('content')
<div class="mb-6"><a href="{{ route('admin.questions.index') }}" class="text-sm font-bold text-blue-700">← Bank soal</a><h1 class="mt-2 font-display text-3xl font-semibold text-slate-800">Buat tryout series</h1></div>
<form method="POST" action="{{ route('admin.questions.series.store') }}" class="max-w-2xl space-y-5 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if ($errors->any())<div class="rounded-md bg-red-50 p-4 text-sm font-semibold text-red-700">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
    <div><label for="name" class="label">Nama series</label><input id="name" name="name" value="{{ old('name') }}" placeholder="Tryout Wajib Series 1" required class="input"></div>
    <div><label for="type" class="label">Tipe tryout</label><select id="type" name="type" required class="input"><option value="wajib" @selected(old('type') === 'wajib')>Wajib</option><option value="pilihan" @selected(old('type') === 'pilihan')>Pilihan</option></select></div>
    <div><label for="status" class="label">Status</label><select id="status" name="status" required class="input"><option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option><option value="active" @selected(old('status') === 'active')>Aktif</option></select></div>
    <div class="flex gap-3"><button class="btn-primary">Simpan series</button><a href="{{ route('admin.questions.index') }}" class="btn-soft">Batal</a></div>
</form>
@endsection