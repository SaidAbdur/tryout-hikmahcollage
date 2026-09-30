@extends('layouts.admin')
@section('title', 'Edit '.$student->name)

@section('content')
<div class="mx-auto max-w-2xl">
    <h1 class="font-display text-3xl font-semibold text-slate-800">Edit student</h1>
    <p class="mb-6 font-semibold text-slate-400">{{ $student->student_id }}</p>

    <form method="POST" action="{{ route('admin.students.update', $student) }}" class="card">
        @csrf @method('PUT')
        @include('partials.student-fields', ['student' => $student])

        <div class="flex gap-3">
            <button class="btn-primary">Save changes</button>
            <a href="{{ route('admin.students.index') }}" class="btn-soft">Cancel</a>
        </div>
    </form>
</div>
@endsection
