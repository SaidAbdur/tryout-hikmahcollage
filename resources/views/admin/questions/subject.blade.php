@extends('layouts.admin')
@section('title', $subject->name)

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div>
        <a href="{{ route('admin.questions.series', $tryoutSeries) }}" class="text-sm font-bold text-blue-700">← {{ $tryoutSeries->name }}</a>
        <p class="mt-2 text-sm font-bold uppercase tracking-widest text-blue-700">{{ $tryoutSeries->name }}</p>
        <h1 class="mt-1 font-display text-3xl font-semibold text-slate-800">{{ $subject->name }}</h1>
    </div>
    <a href="{{ route('admin.questions.create', [$tryoutSeries, $subject]) }}" class="btn-primary"><i data-lucide="plus" class="h-5 w-5"></i> Tambah Soal</a>
</div>

<div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
    <table class="w-full min-w-[48rem] text-left text-sm">
        <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500"><tr><th class="px-5 py-4">#</th><th class="py-4">Pertanyaan</th><th class="py-4">Kunci</th><th class="px-5 py-4 text-right">Aksi</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($questions as $question)
                <tr>
                    <td class="px-5 py-4 text-slate-400">{{ $questions->firstItem() + $loop->index }}</td>
                    <td class="py-4 font-semibold text-slate-800">{{ Str::limit($question->question_text, 140) }}</td>
                    <td class="py-4"><span class="rounded bg-lime-100 px-2 py-1 font-black text-lime-800">{{ $question->correct_option }}</span></td>
                    <td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.questions.edit', [$tryoutSeries, $subject, $question]) }}" class="btn-soft !rounded-md !px-3 !py-2">Edit</a><form method="POST" action="{{ route('admin.questions.destroy', [$tryoutSeries, $subject, $question]) }}" onsubmit="return confirm('Hapus soal ini?')">@csrf @method('DELETE')<button class="btn-danger !rounded-md !px-3 !py-2">Hapus</button></form></div></td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-5 py-12 text-center text-slate-500">Belum ada soal untuk subject dan series ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-5">{{ $questions->links() }}</div>
@endsection