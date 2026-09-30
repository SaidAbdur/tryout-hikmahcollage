@php
    $pct = (float) $session->score;
    $circ = 2 * pi() * 54;
    $pretty = rtrim(rtrim(number_format($pct, 1), '0'), '.');
@endphp

<div class="my-4 flex flex-col items-center gap-4">
    <div class="relative">
        <svg viewBox="0 0 120 120" class="h-40 w-40 -rotate-90" aria-hidden="true">
            <circle cx="60" cy="60" r="54" fill="none" stroke-width="12" class="stroke-slate-100"/>
            <circle cx="60" cy="60" r="54" fill="none" stroke-width="12" stroke-linecap="round" class="stroke-violet-500"
                    stroke-dasharray="{{ round($circ, 1) }}" stroke-dashoffset="{{ round($circ * (1 - $pct / 100), 1) }}"/>
        </svg>
        <div class="absolute inset-0 grid place-content-center text-center">
            <span class="font-display text-4xl font-semibold text-slate-800">{{ $pretty }}</span>
            <span class="text-xs font-bold text-slate-400">dari 100</span>
        </div>
    </div>

    <span class="chip {{ $badgeClass }} text-sm">{{ $emoji }} {{ $label }}</span>

    <div class="grid w-full grid-cols-3 gap-3">
        <div class="rounded-2xl bg-emerald-50 p-3"><p class="font-display text-2xl font-semibold text-emerald-600">{{ $session->total_correct }}</p><p class="text-xs font-bold text-emerald-700">Benar</p></div>
        <div class="rounded-2xl bg-rose-50 p-3"><p class="font-display text-2xl font-semibold text-rose-500">{{ $session->total_wrong }}</p><p class="text-xs font-bold text-rose-600">Salah</p></div>
        <div class="rounded-2xl bg-slate-50 p-3"><p class="font-display text-2xl font-semibold text-slate-500">{{ $unanswered }}</p><p class="text-xs font-bold text-slate-500">Kosong</p></div>
    </div>

    <p class="text-sm font-bold text-slate-500">Persentase {{ number_format($pct, 1) }}%, {{ $session->total_correct }} dari {{ $total }} soal benar.</p>
</div>
