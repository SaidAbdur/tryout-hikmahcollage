@php
    $sets = [
        'Data siswa' => [
            ['name', 'Nama lengkap', 'text', 'sm:col-span-2'],
            ['dob', 'Tanggal lahir', 'date', ''],
            ['class_grade', 'Kelas', 'select', ''],
            ['school_name', 'Nama sekolah', 'text', ''],
            ['address', 'Kota / alamat', 'text', 'sm:col-span-2'],
        ],
        'Data orang tua atau wali' => [
            ['parent_name', 'Nama orang tua / wali', 'text', ''],
            ['parent_wa', 'Nomor WhatsApp', 'tel', ''],
            ['parent_email', 'Email (boleh dikosongkan)', 'email', 'sm:col-span-2'],
        ],
    ];
@endphp

@foreach ($sets as $title => $fields)
    <fieldset class="mb-6">
        <legend class="mb-3 font-display text-xl font-semibold text-violet-600">{{ $title }}</legend>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($fields as [$name, $label, $type, $span])
                @php $val = old($name, $name === 'dob' ? $student?->dob?->format('Y-m-d') : $student?->{$name}); @endphp
                <div class="{{ $span }}">
                    <label for="{{ $name }}" class="label">{{ $label }}</label>
                    @if ($type === 'select')
                        <select id="{{ $name }}" name="{{ $name }}" required class="input">
                            <option value="">Pilih kelas</option>
                            @foreach (\App\Models\Student::GRADES as $g)
                                <option value="{{ $g }}" @selected($val === $g)>{{ $g }}</option>
                            @endforeach
                        </select>
                    @else
                        <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ $val }}"
                               class="input" @required($name !== 'parent_email')
                               @if ($name === 'parent_wa') placeholder="08123456789" inputmode="tel" @endif>
                    @endif
                    @error($name)<p class="mt-1 text-sm font-semibold text-rose-500">{{ $message }}</p>@enderror
                </div>
            @endforeach
        </div>
    </fieldset>
@endforeach
