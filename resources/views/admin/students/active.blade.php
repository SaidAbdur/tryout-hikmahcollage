@extends('layouts.admin')
@section('title', 'Siswa aktif')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <p class="text-sm font-bold uppercase tracking-widest text-blue-700">Siswa</p>
        <h1 class="mt-1 font-display text-3xl font-semibold text-slate-800">Siswa aktif <span class="text-slate-400">({{ $students->total() }})</span></h1>
    </div>
    <a href="{{ route('admin.students.export') }}" class="inline-flex items-center gap-2 rounded-md border border-emerald-700 px-4 py-2.5 font-bold text-emerald-800 transition hover:bg-emerald-50">
        <i data-lucide="download" class="h-5 w-5"></i> Export Data Siswa (CSV)
    </a>
</div>

<div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
    <table class="w-full min-w-[42rem] text-left text-sm">
        <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500"><tr><th class="px-5 py-4">Siswa</th><th class="py-4">Sekolah</th><th class="py-4">Tryout selesai</th><th class="px-5 py-4 text-right">Aksi</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($students as $student)
                <tr>
                    <td class="px-5 py-4"><strong class="text-slate-800">{{ $student->name }}</strong><p class="mt-1 text-xs text-slate-500">{{ $student->student_id }} · {{ $student->parent_email }}</p></td>
                    <td class="py-4">{{ $student->school }}</td>
                    <td class="py-4">{{ $student->completed_sessions }}</td>
                    <td class="px-5 py-4 text-right"><a href="{{ route('admin.students.performance', $student) }}" class="btn-primary !rounded-md !px-3 !py-2">Lihat Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-5 py-12 text-center text-slate-500">Belum ada siswa aktif.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-5">{{ $students->links() }}</div>
@endsection