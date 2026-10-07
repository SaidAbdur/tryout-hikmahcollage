@extends('layouts.cbt', ['hideHeader' => true, 'hideFooter' => true])

@section('content')
<div class="flex-grow flex items-center justify-center p-4 py-10">
    <section class="w-full max-w-xl rounded-3xl bg-white p-8 text-center shadow-2xl md:p-12">
        <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-lime-100 text-hikmah-bluedark">
            <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
        <p class="mt-6 text-sm font-black uppercase tracking-widest text-hikmah-blue">Pendaftaran diterima</p>
        <h1 class="mt-2 text-3xl font-black text-gray-900">Menunggu konfirmasi Admin</h1>
        <p class="mt-4 leading-relaxed text-gray-600">
            Bukti untuk akun <strong>{{ $student->student_id }}</strong> sudah dikirim. Akun Anda berstatus pending dan baru dapat digunakan setelah Admin menyetujui pendaftaran.
        </p>
        <div class="mt-6 rounded-2xl bg-gray-50 p-4 text-left text-sm">
            <p><span class="font-bold text-gray-500">Paket</span> <span class="float-right font-extrabold text-gray-900">{{ $student->package_type === 'free' ? 'Free' : 'Paid · Rp 29.000' }}</span></p>
            <p class="mt-2"><span class="font-bold text-gray-500">Status</span> <span class="float-right rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-800">Menunggu verifikasi</span></p>
        </div>
        <a href="{{ route('login') }}" class="mt-7 inline-flex w-full items-center justify-center rounded-xl bg-hikmah-blue px-5 py-4 font-black text-white transition hover:bg-hikmah-bluedark">KEMBALI KE LOGIN</a>
    </section>
</div>
@endsection