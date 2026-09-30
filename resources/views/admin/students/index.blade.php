@extends('layouts.admin')
@section('title', 'Students')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="font-display text-3xl font-semibold text-slate-800">Students <span class="text-slate-400">({{ $students->total() }})</span></h1>
    <a href="{{ route('admin.students.export', request()->query()) }}" class="btn-primary"><i data-lucide="download" class="h-5 w-5"></i> Export to Excel</a>
</div>

<form method="GET" class="card mb-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-6">
    <input name="q" value="{{ request('q') }}" placeholder="Search name, student ID or parent" class="input xl:col-span-2">
    <select name="grade" class="input">
        <option value="">All classes</option>
        @foreach ($grades as $g)<option value="{{ $g }}" @selected(request('grade') === $g)>{{ $g }}</option>@endforeach
    </select>
    <input name="school" value="{{ request('school') }}" placeholder="School name" class="input">
    <input type="date" name="from" value="{{ request('from') }}" class="input" aria-label="Registered from">
    <input type="date" name="to" value="{{ request('to') }}" class="input" aria-label="Registered until">
    <div class="flex gap-2 sm:col-span-2 xl:col-span-6">
        <button class="btn-primary"><i data-lucide="search" class="h-5 w-5"></i> Apply filters</button>
        <a href="{{ route('admin.students.index') }}" class="btn-soft">Reset</a>
    </div>
</form>

<div class="card overflow-x-auto p-0">
    <table class="w-full min-w-[56rem] text-left text-sm">
        <thead class="bg-slate-50 text-xs font-bold text-slate-400">
            <tr>
                <th class="px-5 py-3">Student</th><th class="py-3">Class</th><th class="py-3">School</th>
                <th class="py-3">Parent</th><th class="py-3">Registered</th><th class="py-3">Tryouts</th><th class="px-5 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($students as $s)
                <tr class="align-middle">
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.students.show', $s) }}" class="font-bold text-slate-800 hover:text-violet-600">{{ $s->name }}</a>
                        <p class="text-xs font-semibold text-slate-400">{{ $s->student_id }}</p>
                    </td>
                    <td>{{ $s->class_grade }}</td>
                    <td>{{ $s->school_name }}</td>
                    <td>
                        {{ $s->parent_name }}
                        <p class="text-xs text-slate-400">{{ $s->parent_wa }}</p>
                    </td>
                    <td>{{ $s->created_at->format('d M Y') }}</td>
                    <td>{{ $s->attempts }}</td>
                    <td class="px-5">
                        <div class="flex justify-end gap-1">
                            <a href="{{ route('admin.students.show', $s) }}" class="btn-soft !px-3 !py-2" title="View progress"><i data-lucide="trending-up" class="h-4 w-4"></i></a>
                            <a href="{{ route('admin.students.edit', $s) }}" class="btn-soft !px-3 !py-2" title="Edit"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                            <form method="POST" action="{{ route('admin.students.destroy', $s) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($s->name) }} and all of their tryout results?')">
                                @csrf @method('DELETE')
                                <button class="btn-danger !px-3 !py-2" title="Delete"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-12 text-center text-slate-400">No students match these filters.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $students->links() }}</div>
@endsection
