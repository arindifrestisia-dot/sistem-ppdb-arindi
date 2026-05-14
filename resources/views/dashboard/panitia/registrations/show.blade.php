<x-panitia-layout title="Verifikasi Berkas Pendaftaran">
    @php
        $backQuery = array_filter([
            'segment' => request('segment'),
            'q' => request('q'),
            'class' => request('class'),
            'ta' => request('ta'),
        ], fn ($value) => $value !== null && $value !== '');
    @endphp
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_420px]">
        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ $registration->full_name }}</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $registration->registration_number ?? 'Belum submit final' }} | {{ $registration->user?->email }}</p>
                </div>
                <a href="{{ route('panitia.registrations.index', $backQuery) }}" class="rounded-full bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700">Kembali</a>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-3xl bg-slate-50 p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Data Anak</p>
                    <div class="mt-4 space-y-2 text-sm text-slate-700">
                        <p><span class="font-semibold">Panggilan:</span> {{ $registration->nickname }}</p>
                        <p><span class="font-semibold">Jenis Kelamin:</span> {{ $registration->gender }}</p>
                        <p><span class="font-semibold">Tempat/Tanggal Lahir:</span> {{ $registration->birth_place }}, {{ optional($registration->birth_date)->format('d-m-Y') }}</p>
                        <p><span class="font-semibold">Asal Daerah:</span> {{ $registration->origin_region }}</p>
                        <p><span class="font-semibold">Alamat:</span> {{ $registration->home_address }}</p>
                    </div>
                </div>
                <div class="rounded-3xl bg-slate-50 p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Data Orang Tua</p>
                    <div class="mt-4 space-y-2 text-sm text-slate-700">
                        <p><span class="font-semibold">Ayah:</span> {{ $registration->father_name }} ({{ $registration->father_phone }})</p>
                        <p><span class="font-semibold">Ibu:</span> {{ $registration->mother_name }} ({{ $registration->mother_phone }})</p>
                        <p><span class="font-semibold">Diverifikasi oleh:</span> {{ $registration->verifier?->name ?? '-' }}</p>
                        <p><span class="font-semibold">Waktu verifikasi:</span> {{ optional($registration->verified_at)->format('d-m-Y H:i') ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 rounded-3xl border border-slate-200 p-5">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Berkas Terunggah</p>
                <div class="mt-4 grid gap-3 md:grid-cols-2">
                    @foreach ([
                        'Foto Anak' => $registration->child_photo_path,
                        'KTP Orang Tua' => $registration->parents_id_card_path,
                        'Akta Lahir' => $registration->birth_certificate_path,
                        'Kartu Keluarga' => $registration->family_card_path,
                    ] as $label => $path)
                        <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm">
                            <p class="font-semibold text-slate-800">{{ $label }}</p>
                            @if ($path)
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" class="mt-2 inline-flex font-semibold text-sky-700">Buka berkas</a>
                            @else
                                <p class="mt-2 text-slate-500">Belum diunggah</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <h3 class="text-2xl font-bold text-slate-900">Kelola Verifikasi</h3>
            <form method="POST" action="{{ route('panitia.registrations.update', $registration) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')
                @foreach ($backQuery as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $registration->full_name) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Panggilan</label>
                    <input type="text" name="nickname" value="{{ old('nickname', $registration->nickname) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Jenis Kelamin</label>
                        <select name="gender" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                            <option value="Laki-laki" @selected(old('gender', $registration->gender) === 'Laki-laki')>Laki-laki</option>
                            <option value="Perempuan" @selected(old('gender', $registration->gender) === 'Perempuan')>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', optional($registration->birth_date)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Tempat Lahir</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place', $registration->birth_place) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Alamat</label>
                    <textarea name="home_address" rows="3" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">{{ old('home_address', $registration->home_address) }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Asal Daerah</label>
                    <input type="text" name="origin_region" value="{{ old('origin_region', $registration->origin_region) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Ayah</label>
                        <input type="text" name="father_name" value="{{ old('father_name', $registration->father_name) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">No. Ayah</label>
                        <input type="text" name="father_phone" value="{{ old('father_phone', $registration->father_phone) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Ibu</label>
                        <input type="text" name="mother_name" value="{{ old('mother_name', $registration->mother_name) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">No. Ibu</label>
                        <input type="text" name="mother_phone" value="{{ old('mother_phone', $registration->mother_phone) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Status Verifikasi</label>
                    <select name="verification_status" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                        @foreach (['belum_diperiksa', 'revisi', 'terverifikasi', 'ditolak'] as $statusOption)
                            <option value="{{ $statusOption }}" @selected(old('verification_status', $registration->verification_status) === $statusOption)>{{ str_replace('_', ' ', $statusOption) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Catatan Verifikasi</label>
                    <textarea name="verification_notes" rows="4" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">{{ old('verification_notes', $registration->verification_notes) }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Hasil Seleksi</label>
                    <select name="selection_result" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                        <option value="">Belum ditentukan</option>
                        <option value="lulus" @selected(old('selection_result', $registration->selection_result) === 'lulus')>Lulus</option>
                        <option value="tidak_lulus" @selected(old('selection_result', $registration->selection_result) === 'tidak_lulus')>Tidak Lulus</option>
                    </select>
                </div>
                <label class="flex items-center gap-3 rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                    <input type="checkbox" name="publish_selection" value="1" @checked(old('publish_selection', $registration->selection_published_at !== null))>
                    Tampilkan hasil seleksi ke dashboard orang tua
                </label>
                <button type="submit" class="w-full rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Simpan Perubahan</button>
            </form>
        </section>
    </div>
</x-panitia-layout>
