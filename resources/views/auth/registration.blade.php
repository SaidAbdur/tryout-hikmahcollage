@extends('layouts.cbt', ['hideHeader' => true, 'hideFooter' => true])

@section('content')
<div class="flex-grow flex items-center justify-center p-4 py-10">
    <section class="w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl">
        <header class="bg-hikmah-blue px-7 py-6 text-white md:px-9">
            <div class="flex items-center justify-between gap-4">
                <div><p class="text-sm font-bold uppercase tracking-widest text-blue-100">Hikmah College</p><h1 class="mt-1 text-3xl font-black">Registrasi Peserta</h1></div>
                <a href="{{ route('login') }}" class="rounded-lg bg-white/15 px-4 py-2 text-sm font-bold hover:bg-white/25">Masuk</a>
            </div>
            <p class="mt-2 max-w-2xl text-sm text-blue-100">Isi data siswa dan orang tua, buat password, lalu pilih paket tryout.</p>
        </header>

        <div class="p-6 md:p-9">
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700">
                    <p class="mb-1">Periksa kembali data berikut:</p>
                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="space-y-6" x-data="{ dob: '{{ old('dob') }}', age: null, calculateAge() { if (!this.dob) { this.age = null; return; } const [year, month, day] = this.dob.split('-').map(Number); const today = new Date(); let age = today.getFullYear() - year; if (today.getMonth() + 1 < month || (today.getMonth() + 1 === month && today.getDate() < day)) age--; this.age = age >= 0 ? age : null; } }" x-init="calculateAge()">
                @csrf
                <section>
                    <h2 class="mb-4 border-b border-slate-100 pb-2 text-sm font-black uppercase tracking-widest text-blue-800">Data siswa</h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2"><label class="mb-1 block text-sm font-bold text-slate-700" for="name">Nama lengkap</label><input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></div>
                        <div><label class="mb-1 block text-sm font-bold text-slate-700" for="phone">Nomor WhatsApp siswa</label><input id="phone" name="phone" value="{{ old('phone') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></div>
                        <div><label class="mb-1 block text-sm font-bold text-slate-700" for="region">Kota / Kabupaten</label><input id="region" name="region" value="{{ old('region') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></div>
                        <div><label class="mb-1 block text-sm font-bold text-slate-700" for="school">Asal sekolah</label><input id="school" name="school" value="{{ old('school') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></div>
                        <div><label class="mb-1 block text-sm font-bold text-slate-700" for="grade_level">Jenjang Kelas</label><select id="grade_level" name="grade_level" required class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"><option value="">Pilih jenjang kelas</option><option value="Kelas 10" @selected(old('grade_level') === 'Kelas 10')>Kelas 10</option><option value="Kelas 11" @selected(old('grade_level') === 'Kelas 11')>Kelas 11</option><option value="Kelas 12" @selected(old('grade_level') === 'Kelas 12')>Kelas 12</option></select></div>
                        <div><label class="mb-1 block text-sm font-bold text-slate-700" for="dob">Tanggal lahir</label><input id="dob" type="date" name="dob" x-model="dob" @input="calculateAge()" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"><span x-cloak x-show="age !== null" class="mt-2 inline-flex rounded-full bg-lime-200 px-3 py-1 text-sm font-extrabold text-lime-900">Usia Terdeteksi: <span class="ml-1" x-text="age"></span> Tahun</span></div>
                        <fieldset class="md:col-span-2"><legend class="mb-2 text-sm font-bold text-slate-700">Jenis kelamin</legend><div class="flex gap-6"><label class="flex items-center gap-2"><input type="radio" name="gender" value="Laki-laki" @checked(old('gender') === 'Laki-laki') required> Laki-laki</label><label class="flex items-center gap-2"><input type="radio" name="gender" value="Perempuan" @checked(old('gender') === 'Perempuan') required> Perempuan</label></div></fieldset>
                    </div>
                </section>

                <section>
                    <h2 class="mb-4 border-b border-slate-100 pb-2 text-sm font-black uppercase tracking-widest text-blue-800">Data orang tua / wali</h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div><label class="mb-1 block text-sm font-bold text-slate-700" for="parent_name">Nama orang tua</label><input id="parent_name" name="parent_name" value="{{ old('parent_name') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></div>
                        <div><label class="mb-1 block text-sm font-bold text-slate-700" for="parent_phone">Nomor WA orang tua</label><input id="parent_phone" name="parent_phone" value="{{ old('parent_phone') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></div>
                        <div class="md:col-span-2"><label class="mb-1 block text-sm font-bold text-slate-700" for="parent_email">Email orang tua</label><input id="parent_email" type="email" name="parent_email" value="{{ old('parent_email') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></div>
                    </div>
                </section>

                <section>
                    <h2 class="mb-4 border-b border-slate-100 pb-2 text-sm font-black uppercase tracking-widest text-blue-800">Password dan paket</h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div><label class="mb-1 block text-sm font-bold text-slate-700" for="password">Password</label><input id="password" type="password" name="password" minlength="8" autocomplete="new-password" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></div>
                        <div><label class="mb-1 block text-sm font-bold text-slate-700" for="password_confirmation">Konfirmasi password</label><input id="password_confirmation" type="password" name="password_confirmation" minlength="8" autocomplete="new-password" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></div>
                        <div class="md:col-span-2"><label class="mb-1 block text-sm font-bold text-slate-700" for="package_type">Pilih paket</label><select id="package_type" name="package_type" required class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 font-semibold outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"><option value="">Pilih paket tryout</option><option value="free" @selected(old('package_type') === 'free')>Free · unggah 5 bukti berbagi WhatsApp</option><option value="paid" @selected(old('package_type') === 'paid')>Paid · Rp 29.000 · unggah 1 bukti transfer</option></select></div>
                    </div>
                </section>

                <button class="w-full rounded-xl bg-hikmah-neon px-5 py-4 font-black text-hikmah-bluedark transition hover:bg-hikmah-neondark">LANJUTKAN KE UNGGAH BUKTI</button>
            </form>
        </div>
    </section>
</div>
@endsection