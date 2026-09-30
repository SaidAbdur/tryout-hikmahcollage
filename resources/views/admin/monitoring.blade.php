@extends('layouts.admin')
@section('title', 'Live monitoring')

@section('content')
<div x-data="monitor('{{ route('admin.monitoring.data') }}')">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="font-display text-3xl font-semibold text-slate-800">Live monitoring</h1>
        <span class="chip bg-emerald-100 text-sm text-emerald-700">
            <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-500"></span>
            <span x-text="live + (live === 1 ? ' student' : ' students') + ' taking a test now'"></span>
        </span>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full min-w-[44rem] text-left text-sm">
            <thead class="text-xs font-bold text-slate-400">
                <tr><th class="pb-3">Student</th><th class="pb-3">Subject</th><th class="pb-3">Answered</th><th class="pb-3">Time left</th><th class="pb-3">Status</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <template x-for="r in rows" :key="r.id">
                    <tr class="align-middle">
                        <td class="py-3">
                            <a :href="r.url" class="font-bold text-slate-800 hover:text-violet-600" x-text="r.student"></a>
                            <p class="text-xs font-semibold text-slate-400" x-text="r.student_id + ', ' + r.grade"></p>
                        </td>
                        <td class="font-semibold" x-text="r.subject"></td>
                        <td>
                            <div class="h-2 w-32 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-violet-400 transition-all" :style="'width:' + (r.total ? Math.round(r.answered / r.total * 100) : 0) + '%'"></div>
                            </div>
                            <p class="mt-1 text-xs font-semibold text-slate-400" x-text="r.answered + ' of ' + r.total"></p>
                        </td>
                        <td class="font-display text-lg font-semibold"
                            :class="r.status === 'in_progress' && r.remaining < 300 ? 'text-rose-500' : 'text-slate-700'"
                            x-text="r.status === 'in_progress' ? fmt(r.remaining) : (r.score !== null ? 'Score ' + r.score : '-')"></td>
                        <td>
                            <span class="chip" :class="r.status === 'in_progress' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700'"
                                  x-text="r.status === 'in_progress' ? 'In progress' : 'Completed'"></span>
                        </td>
                    </tr>
                </template>
                <tr x-show="rows.length === 0"><td colspan="5" class="py-12 text-center text-slate-400">No test activity today. Sessions appear here as soon as a student starts.</td></tr>
            </tbody>
        </table>
    </div>
    <p class="mt-3 text-sm font-semibold text-slate-400">Refreshes every 8 seconds.</p>
</div>
@endsection
