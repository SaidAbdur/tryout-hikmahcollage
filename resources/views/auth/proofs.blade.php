@extends('layouts.cbt', ['hideHeader' => true, 'hideFooter' => true])

@section('content')
<div class="flex-grow flex items-center justify-center p-4 py-10">
    <section class="w-full max-w-2xl overflow-hidden rounded-3xl bg-white shadow-2xl">
        <div class="bg-hikmah-blue px-8 py-7 text-white">
            <p class="text-sm font-bold uppercase tracking-widest text-blue-100">Langkah 2 dari 2</p>
            <h1 class="mt-2 text-3xl font-black">Unggah bukti pendaftaran</h1>
            <p class="mt-2 text-blue-100">Student ID: <strong>{{ $student->student_id }}</strong></p>
        </div>

        <div class="p-8">
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700">
                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            @if ($student->package_type === 'free')
                <div class="mb-6 rounded-2xl border border-lime-200 bg-lime-50 p-5">
                    <h2 class="font-extrabold text-gray-900">Paket Free</h2>
                    <p class="mt-1 text-sm text-gray-700">Unggah tepat 5 screenshot bukti berbagi informasi tryout ke grup WhatsApp.</p>
                </div>
            @else
                <div class="mb-6 rounded-2xl border border-blue-100 bg-blue-50 p-5">
                    <h2 class="font-extrabold text-gray-900">Paket Paid · Rp 29.000</h2>
                    <p class="mt-1 text-sm text-gray-700">Selesaikan pembayaran sesuai instruksi admin, lalu unggah 1 screenshot bukti transfer.</p>
                </div>
            @endif

            <form method="POST" action="{{ route('registration.proofs.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label for="proofs" class="mb-2 block text-sm font-bold text-gray-700">
                        {{ $student->package_type === 'free' ? '5 file gambar' : '1 file gambar' }}
                    </label>
                    <input id="proofs" type="file" name="proofs[]" accept="image/*" required
                           @if ($student->package_type === 'free') multiple @endif
                           class="block w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-hikmah-blue file:px-4 file:py-2 file:font-bold file:text-white">
                    <p class="mt-2 text-xs text-gray-500">Format gambar, maksimal 5 MB per file.</p>
                </div>
                <button type="submit" class="w-full rounded-xl bg-hikmah-neon px-5 py-4 font-black text-hikmah-bluedark transition hover:bg-hikmah-neondark">
                    KIRIM BUKTI UNTUK VERIFIKASI
                </button>
            </form>
        </div>
    </section>
</div>
@endsection