@extends('layouts.admin')
@section('title', $student->name)

@section('content')
@php
    $wa = preg_replace('/\D/', '', $student->parent_wa);
    $wa = preg_replace('/^0/', '62', $wa);
    $pretty = fn ($n) => rtrim(rtrim(number_format($n, 1), '0'), '.');
@endphp

<a href="{{ route('admin.students.index') }}" class="btn-soft mb-4"><i data-lucide="chevron-left" class="h-5 w-5"></i> All students</a>

<div class="grid gap-6 xl:grid-cols-3">
    <div class="card">
        <h1 class="font-display text-2xl font-semibold text-slate-800">{{ $student->name }}</h1>
        <span class="chip mt-1 bg-violet-100 text-violet-700">{{ $student->student_id }}</span>

        <dl class="mt-5 space-y-3 text-sm">
            <div><dt class="font-bold text-slate-400">Class</dt><dd class="font-semibold">{{ $student->class_grade }}</dd></div>
            <div><dt class="font-bold text-slate-400">School</dt><dd class="font-semibold">{{ $student->school_name }}</dd></div>
            <div><dt class="font-bold text-slate-400">Date of birth</dt><dd class="font-semibold">{{ $student->dob->format('d M Y') }}</dd></div>
            <div><dt class="font-bold text-slate-400">Address</dt><dd class="font-semibold">{{ $student->address }}</dd></div>
            <div><dt class="font-bold text-slate-400">Parent</dt>
                <dd class="font-semibold">{{ $student->parent_name }}<br>
                    <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="text-emerald-600 hover:underline">{{ $student->parent_wa }} (WhatsApp)</a>
                    @if ($student->parent_email)<br>{{ $student->parent_email }}
                        <span class="chip ml-1 {{ $student->email_verified_at ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $student->email_verified_at ? 'Verified' : 'Not verified' }}</span>
                    @endif
                </dd></div>
        </dl>

        <div class="mt-5 flex gap-2">
            <a href="{{ route('admin.students.edit', $student) }}" class="btn-soft"><i data-lucide="pencil" class="h-4 w-4"></i> Edit</a>
        </div>
    </div>

    <div class="space-y-6 xl:col-span-2">
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            @foreach ([['Tryouts done', $done->count()], ['Average', $done->isEmpty() ? '-' : $pretty($done->avg('score'))], ['Best', $done->isEmpty() ? '-' : $pretty($done->max('score'))], ['Latest', $done->isEmpty() ? '-' : $pretty($done->last()->score)]] as [$label, $value])
                <div class="card !p-4 text-center"><p class="font-display text-2xl font-semibold text-violet-600">{{ $value }}</p><p class="text-xs font-bold text-slate-500">{{ $label }}</p></div>
            @endforeach
        </div>

        @if ($done->isEmpty())
            <div class="card py-12 text-center text-slate-400">This student has not completed a tryout yet.</div>
        @else
            <div class="card" x-data="chartBox(@js($progressChart))">
                <h2 class="mb-4 font-display text-xl font-semibold text-slate-800">Score progress</h2>
                <div class="relative h-64"><canvas x-ref="c"></canvas></div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="card" x-data="chartBox(@js($subjectChart))">
                    <h2 class="mb-4 font-display text-xl font-semibold text-slate-800">Average per subject</h2>
                    <div class="relative h-56"><canvas x-ref="c"></canvas></div>
                </div>
                <div class="card">
                    <h2 class="mb-3 font-display text-xl font-semibold text-slate-800">Strengths and weaknesses</h2>
                    <p class="mb-2 text-sm font-bold text-emerald-600">Strongest subjects</p>
                    <div class="mb-5 flex flex-wrap gap-2">
                        @foreach ($strong as $name => $avg)<span class="chip bg-emerald-100 text-emerald-700">{{ $name }} {{ $pretty($avg) }}</span>@endforeach
                    </div>
                    <p class="mb-2 text-sm font-bold text-rose-500">Needs more practice</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($weak as $name => $avg)<span class="chip bg-rose-100 text-rose-600">{{ $name }} {{ $pretty($avg) }}</span>@endforeach
                    </div>
                </div>
            </div>

            <div class="card overflow-x-auto">
                <h2 class="mb-4 font-display text-xl font-semibold text-slate-800">Attempt history</h2>
                <table class="w-full min-w-[28rem] text-left text-sm">
                    <thead class="text-xs font-bold text-slate-400"><tr><th class="pb-3">Subject</th><th class="pb-3">Submitted</th><th class="pb-3">Correct</th><th class="pb-3">Wrong</th><th class="pb-3 text-right">Score</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($done->reverse() as $s)
                            <tr>
                                <td class="py-3 font-bold text-slate-800">{{ $s->subject->name }}</td>
                                <td>{{ $s->submitted_at->format('d M Y, H:i') }}</td>
                                <td class="text-emerald-600">{{ $s->total_correct }}</td>
                                <td class="text-rose-500">{{ $s->total_wrong }}</td>
                                <td class="text-right font-display text-lg font-semibold text-violet-600">{{ $pretty($s->score) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
