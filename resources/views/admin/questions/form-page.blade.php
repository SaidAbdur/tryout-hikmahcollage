@extends('layouts.admin')
@section('title', $question->exists ? 'Edit soal' : 'Tambah soal')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.questions.subject', [$tryoutSeries, $subject]) }}" class="text-sm font-bold text-blue-700">← {{ $subject->name }}</a>
    <p class="mt-2 text-sm font-bold uppercase tracking-widest text-blue-700">{{ $tryoutSeries->name }}</p>
    <h1 class="mt-1 font-display text-3xl font-semibold text-slate-800">{{ $question->exists ? 'Edit soal' : 'Tambah soal' }} · {{ $subject->name }}</h1>
</div>

<form method="POST" action="{{ $question->exists ? route('admin.questions.update', [$tryoutSeries, $subject, $question]) : route('admin.questions.store', [$tryoutSeries, $subject]) }}" class="max-w-4xl space-y-5 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if ($question->exists) @method('PUT') @endif
    @if ($errors->any())<div class="rounded-md bg-red-50 p-4 text-sm font-semibold text-red-700">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
    <div><label for="question_text" class="label">Teks pertanyaan</label><textarea id="question_text" name="question_text" rows="4" required class="input">{{ old('question_text', $question->question_text) }}</textarea></div>
    <div class="grid gap-4 sm:grid-cols-2">
        @foreach (['A', 'B', 'C', 'D', 'E'] as $option)
            @php $field = 'option_'.strtolower($option); @endphp
            <div><label for="{{ $field }}" class="label">Pilihan {{ $option }}</label><textarea id="{{ $field }}" name="{{ $field }}" rows="2" required class="input">{{ old($field, $question->{$field}) }}</textarea></div>
        @endforeach
    </div>
    <div><label for="correct_option" class="label">Kunci jawaban</label><select id="correct_option" name="correct_option" required class="input">@foreach (['A', 'B', 'C', 'D', 'E'] as $option)<option value="{{ $option }}" @selected(old('correct_option', $question->correct_option) === $option)>{{ $option }}</option>@endforeach</select></div>
    <div><label for="explanation_text" class="label">Pembahasan</label><textarea id="explanation_text" name="explanation_text" rows="4" class="input">{{ old('explanation_text', $question->explanation_text) }}</textarea></div>
    <div class="flex gap-3"><button class="btn-primary">Simpan soal</button><a href="{{ route('admin.questions.subject', [$tryoutSeries, $subject]) }}" class="btn-soft">Batal</a></div>
</form>
@endsection