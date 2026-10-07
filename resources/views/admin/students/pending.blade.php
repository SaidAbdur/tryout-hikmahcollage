@extends('layouts.admin')
@section('title', 'Persetujuan siswa')

@section('content')
<div class="mb-6">
    <p class="text-sm font-bold uppercase tracking-widest text-blue-700">Pendaftaran</p>
    <h1 class="mt-1 font-display text-3xl font-semibold text-slate-800">Menunggu persetujuan</h1>
</div>

<div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
    <table class="w-full min-w-[58rem] text-left text-sm">
        <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
            <tr><th class="px-5 py-4">Siswa</th><th class="py-4">Orang tua</th><th class="py-4">Paket</th><th class="py-4">Bukti</th><th class="px-5 py-4 text-right">Tindakan</th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($students as $student)
                <tr class="align-top">
                    <td class="px-5 py-4"><strong class="text-slate-800">{{ $student->name }}</strong><p class="mt-1 text-xs text-slate-500">{{ $student->student_id }} · {{ $student->school }}</p></td>
                    <td class="py-4">{{ $student->parent_name }}<p class="mt-1 text-xs text-slate-500">{{ $student->parent_phone }}<br>{{ $student->parent_email }}</p></td>
                    <td class="py-4"><span class="rounded-full {{ $student->package_type === 'free' ? 'bg-lime-100 text-lime-800' : 'bg-blue-50 text-blue-800' }} px-3 py-1 text-xs font-black">{{ $student->package_type === 'free' ? 'Free' : 'Paid · Rp 29.000' }}</span></td>
                    <td class="py-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($student->proof_files ?? [] as $index => $proof)
                                <a href="{{ route('admin.students.proofs', [$student, $index]) }}" target="_blank" rel="noopener" class="block" aria-label="Buka bukti {{ $index + 1 }}">
                                    <img src="{{ route('admin.students.proofs', [$student, $index]) }}" alt="Bukti {{ $index + 1 }}" class="h-16 w-16 rounded border border-slate-200 object-cover">
                                </a>
                            @endforeach
                            @if (empty($student->proof_files))<span class="text-xs font-semibold text-red-600">Bukti belum diunggah</span>@endif
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-2">
                            <form method="POST" action="{{ route('admin.students.status', $student) }}">@csrf
                                <input type="hidden" name="account_status" value="approved">
                                <button class="rounded-md bg-blue-700 px-3 py-2 font-bold text-white hover:bg-blue-800">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('admin.students.status', $student) }}" onsubmit="return confirm('Tolak pendaftaran ini?')">@csrf
                                <input type="hidden" name="account_status" value="rejected">
                                <button class="rounded-md border border-red-200 px-3 py-2 font-bold text-red-700 hover:bg-red-50">Tolak</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">Tidak ada pendaftaran yang menunggu persetujuan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-5">{{ $students->links() }}</div>
@endsection