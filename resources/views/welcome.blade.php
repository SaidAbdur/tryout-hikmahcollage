@extends('layouts.app')
@section('title', 'Latihan tryout TKA online')

@section('content')
<section class="grid items-center gap-10 py-6 lg:grid-cols-2 lg:py-12">
    <div>
        <h1 class="font-display text-4xl font-semibold leading-tight text-slate-800 sm:text-5xl">
            Latihan TKA jadi seru, hasilnya langsung keluar.
        </h1>
        <p class="mt-4 max-w-md text-lg text-slate-500">
            Kerjakan tryout dengan timer sungguhan, lihat skor begitu selesai, lalu baca pembahasan tiap soal.
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('register') }}" class="btn-primary"><i data-lucide="user-plus" class="h-5 w-5"></i> Daftar gratis</a>
            <a href="{{ route('login') }}" class="btn-soft"><i data-lucide="log-in" class="h-5 w-5"></i> Masuk dengan Student ID</a>
        </div>
    </div>

    {{-- Live sample question: the first thing a visitor can actually do. --}}
    <div class="card pop-in" x-data="{ picked: null, correct: 'C' }">
        <div class="mb-4 flex items-center justify-between">
            <span class="chip bg-sky-100 text-sky-700">Coba satu soal</span>
            <span class="chip bg-violet-100 text-violet-700">Matematika</span>
        </div>
        <p class="text-lg font-bold text-slate-800">Jika 2x + 5 = 17, maka nilai x adalah ...</p>
        <div class="mt-5 space-y-3">
            @foreach (['A' => '4', 'B' => '5', 'C' => '6', 'D' => '7'] as $key => $text)
                <button type="button" @click="picked = '{{ $key }}'" :disabled="picked !== null"
                        class="flex w-full cursor-pointer items-center gap-3 rounded-2xl border-2 p-3 text-left font-semibold transition hover:-translate-y-0.5 disabled:cursor-default disabled:hover:translate-y-0"
                        :class="picked === null ? 'border-slate-200 bg-white hover:border-violet-300'
                            : '{{ $key }}' === correct ? 'border-emerald-300 bg-emerald-50'
                            : picked === '{{ $key }}' ? 'border-rose-300 bg-rose-50' : 'border-slate-100 bg-white opacity-60'">
                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-slate-100 font-extrabold">{{ $key }}</span>{{ $text }}
                </button>
            @endforeach
        </div>
        <div x-show="picked" x-cloak x-transition class="mt-4 rounded-2xl bg-amber-50 p-4 text-sm font-semibold text-slate-600">
            <p class="mb-1 font-extrabold text-amber-700" x-text="picked === correct ? 'Benar! 🎉' : 'Belum tepat, jawaban yang benar C.'"></p>
            2x + 5 = 17, jadi 2x = 12 dan x = 6. Begini bentuk pembahasan setelah kamu selesai tryout.
        </div>
    </div>
</section>

<section class="mt-8 grid gap-5 sm:grid-cols-3">
    @foreach ([['timer', 'bg-violet-100 text-violet-600', 'Timer sungguhan', 'Mapel wajib 75 menit, mapel pilihan 50 sampai 60 menit. Selesai otomatis saat waktu habis.'],
               ['trophy', 'bg-amber-100 text-amber-600', 'Skor dan lencana', 'Dapatkan skor, rincian benar dan salah, plus lencana sesuai nilaimu.'],
               ['lightbulb', 'bg-emerald-100 text-emerald-600', 'Pembahasan lengkap', 'Setiap soal punya penjelasan dari tim akademik, tersedia kapan saja di riwayatmu.']] as [$icon, $tint, $title, $text])
        <div class="card">
            <div class="mb-4 grid h-12 w-12 place-items-center rounded-2xl {{ $tint }}"><i data-lucide="{{ $icon }}" class="h-6 w-6"></i></div>
            <h2 class="font-display text-xl font-semibold text-slate-800">{{ $title }}</h2>
            <p class="mt-1 text-slate-500">{{ $text }}</p>
        </div>
    @endforeach
</section>
@endsection
