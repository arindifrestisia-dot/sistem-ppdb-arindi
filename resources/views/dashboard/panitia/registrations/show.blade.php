@php
    $layoutComponent = auth()->user()?->isKepsek() ? 'kepsek-layout' : 'panitia-layout';
    $registrationRoutePrefix = auth()->user()?->isKepsek() ? 'kepsek' : 'panitia';
@endphp

<x-dynamic-component :component="$layoutComponent" title="Verifikasi Berkas Pendaftaran">
    @php
        $backQuery = array_filter([
            'segment' => request('segment'),
            'q' => request('q'),
            'class' => request('class'),
            'ta' => request('ta'),
        ], fn ($value) => $value !== null && $value !== '');

        $displayValue = fn ($value) => filled($value) ? $value : '-';
        $formatDate = fn ($date) => $date ? $date->translatedFormat('d F Y') : '-';
        $formatDateTime = fn ($date) => $date ? $date->format('d-m-Y H:i') : '-';

        $studentRows = [
            'Kode Pendaftaran' => $registration->registration_number ?? 'Belum submit final',
            'Nama Lengkap' => $registration->full_name,
            'Nama Panggilan' => $registration->nickname,
            'Jenis Kelamin' => $registration->gender,
            'Tempat, Tanggal Lahir' => trim(($registration->birth_place ?? '-') . ', ' . $formatDate($registration->birth_date)),
            'Agama' => $registration->religion,
            'Berat Badan' => $registration->weight_kg ? $registration->weight_kg . ' kg' : '-',
            'Tinggi Badan' => $registration->height_cm ? $registration->height_cm . ' cm' : '-',
            'Kewarganegaraan' => $registration->citizenship,
            'Berkebutuhan Khusus' => $registration->special_needs ? 'Ya' : 'Tidak',
            'Keterangan Kebutuhan Khusus' => $registration->special_needs_description,
            'Status Anak' => $registration->child_status,
            'Golongan Darah' => $registration->blood_type,
            'Anak ke' => $registration->child_order ? $registration->child_order . ' dari ' . $registration->siblings_total . ' bersaudara' : '-',
            'Riwayat Penyakit' => $registration->medical_history,
            'Asal Daerah' => $registration->origin_region,
            'Alamat Rumah (Lengkap)' => $registration->home_address,
            'Email Akun' => $registration->user?->email,
        ];

        $parentRows = [
            'Nama Ayah' => $registration->father_name,
            'Tempat/Tanggal Lahir Ayah' => $registration->father_birth_info,
            'Agama Ayah' => $registration->father_religion,
            'Kewarganegaraan Ayah' => $registration->father_citizenship,
            'Status Ayah' => $registration->father_status,
            'Pekerjaan Ayah' => $registration->father_job,
            'Pendidikan Ayah' => $registration->father_education,
            'Penghasilan Ayah' => $registration->father_income,
            'No. HP Ayah' => $registration->father_phone,
            'Alamat Lengkap Ayah' => $registration->father_address,
            'Email Ayah' => $registration->father_email,
            'Nama Ibu' => $registration->mother_name,
            'Tempat/Tanggal Lahir Ibu' => $registration->mother_birth_info,
            'Agama Ibu' => $registration->mother_religion,
            'Kewarganegaraan Ibu' => $registration->mother_citizenship,
            'Status Ibu' => $registration->mother_status,
            'Pekerjaan Ibu' => $registration->mother_job,
            'Pendidikan Ibu' => $registration->mother_education,
            'Penghasilan Ibu' => $registration->mother_income,
            'No. HP Ibu' => $registration->mother_phone,
            'Alamat Lengkap Ibu' => $registration->mother_address,
            'Email Ibu' => $registration->mother_email,
        ];

        $uploadedFiles = [
            'Foto Anak' => $registration->child_photo_path,
            'KTP Orang Tua' => $registration->parents_id_card_path,
            'Akta Kelahiran' => $registration->birth_certificate_path,
            'Kartu Keluarga' => $registration->family_card_path,
        ];
    @endphp

    <div class="space-y-6">
        <div class="flex flex-wrap items-start justify-between gap-4 rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                <div class="h-32 w-28 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                    @if ($registration->child_photo_path)
                        <img src="{{ asset('storage/' . $registration->child_photo_path) }}" alt="Foto {{ $registration->full_name }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center px-3 text-center text-xs font-semibold text-slate-500">
                            Foto belum diunggah
                        </div>
                    @endif
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ $registration->full_name }}</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $registration->registration_number ?? 'Belum submit final' }} | {{ $registration->user?->email }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route($registrationRoutePrefix . '.registrations.biodata-pdf', $registration) }}" class="rounded-full bg-amber-300 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-200">
                    Export PDF
                </a>
                <a href="{{ route($registrationRoutePrefix . '.registrations.index', $backQuery) }}" class="rounded-full bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Kembali</a>
            </div>
        </div>

        <section class="overflow-hidden rounded-[2rem] bg-white shadow-sm">
            <div class="bg-blue-900 px-6 py-4 text-white">
                <h3 class="text-lg font-bold">Data Anak</h3>
            </div>
            <div class="p-6">
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <table class="min-w-full text-sm">
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($studentRows as $label => $value)
                                <tr>
                                    <th class="w-full bg-slate-50 px-5 py-4 text-left font-bold text-slate-900 sm:w-[34%]">{{ $label }}</th>
                                    <td class="px-5 py-4 text-slate-700">{{ $displayValue($value) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-[2rem] bg-white shadow-sm">
            <div class="bg-blue-900 px-6 py-4 text-white">
                <h3 class="text-lg font-bold">Data Orang Tua</h3>
            </div>
            <div class="p-6">
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <table class="min-w-full text-sm">
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($parentRows as $label => $value)
                                <tr>
                                    <th class="w-full bg-slate-50 px-5 py-4 text-left font-bold text-slate-900 sm:w-[34%]">{{ $label }}</th>
                                    <td class="px-5 py-4 text-slate-700">{{ $displayValue($value) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-[2rem] bg-white shadow-sm">
            <div class="bg-blue-900 px-6 py-4 text-white">
                <h3 class="text-lg font-bold">Berkas yang Diupload</h3>
            </div>
            <div class="grid gap-4 p-6 md:grid-cols-2">
                @foreach ($uploadedFiles as $label => $path)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <p class="font-bold text-slate-900">{{ $label }}</p>
                        @if ($path)
                            <a href="{{ asset('storage/' . $path) }}" target="_blank" class="mt-3 inline-flex rounded-full bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700 transition hover:bg-sky-100">
                                Buka berkas
                            </a>
                        @else
                            <p class="mt-3 text-sm text-slate-500">Belum diunggah</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        @if (auth()->user()?->isPanitia())
            <section class="overflow-hidden rounded-[2rem] bg-white shadow-sm">
                <div class="bg-blue-900 px-6 py-4 text-white">
                    <h3 class="text-lg font-bold">Kelola Verifikasi</h3>
                </div>
                <form method="POST" action="{{ route('panitia.registrations.update', $registration) }}" class="space-y-5 p-6">
                    @csrf
                    @method('PUT')
                    @foreach ($backQuery as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Status Verifikasi</label>
                            <select name="verification_status" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                                @foreach ([
                                    'belum_diperiksa' => 'Belum diperiksa',
                                    'terverifikasi' => 'Diterima',
                                    'ditolak' => 'Ditolak',
                                ] as $statusOption => $statusLabel)
                                    <option value="{{ $statusOption }}" @selected(old('verification_status', $registration->verification_status) === $statusOption)>{{ $statusLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Hasil Seleksi</label>
                            <select name="selection_result" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                                <option value="">Belum ditentukan</option>
                                <option value="lulus" @selected(old('selection_result', $registration->selection_result) === 'lulus')>Lulus</option>
                                <option value="tidak_lulus" @selected(old('selection_result', $registration->selection_result) === 'tidak_lulus')>Tidak Lulus</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Catatan Verifikasi</label>
                        <textarea name="verification_notes" rows="4" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">{{ old('verification_notes', $registration->verification_notes) }}</textarea>
                    </div>

                    <label class="flex items-start gap-3 rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                        <input type="checkbox" name="publish_selection" value="1" class="mt-1" @checked(old('publish_selection', $registration->selection_published_at !== null))>
                        <span>Tampilkan hasil seleksi ke dashboard orang tua</span>
                    </label>

                    <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        <p><span class="font-semibold text-slate-800">Diverifikasi oleh:</span> {{ $registration->verifier?->name ?? '-' }}</p>
                        <p class="mt-1"><span class="font-semibold text-slate-800">Waktu verifikasi:</span> {{ $formatDateTime($registration->verified_at) }}</p>
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-slate-900 px-5 py-4 text-sm font-semibold text-white transition hover:bg-slate-700">
                        Simpan Verifikasi
                    </button>
                </form>
            </section>
        @endif
    </div>
</x-dynamic-component>
