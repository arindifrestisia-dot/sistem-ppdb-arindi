<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#cfe0f8] text-slate-900">
    <div class="flex min-h-screen flex-col md:flex-row">
        @php($activeMenu = 'data-diri')
        @include('dashboard.panel-ortu.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('dashboard.panel-ortu.partials.topbar')

            <main class="flex-1 px-5 py-6 md:px-8">
                <div class="mx-auto max-w-6xl">
                    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                        <div>
                            <h1 class="text-3xl font-extrabold text-blue-950 md:text-5xl">Formulir Pendaftaran</h1>
                            <p class="mt-2 text-lg text-slate-500">Lengkapi data anak, data orang tua/wali, lalu upload berkas untuk melanjutkan proses pendaftaran RA Fadhilah.</p>
                        </div>
                        <span class="inline-flex rounded-2xl bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">Formulir Sudah Aktif</span>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <button type="button" class="step-indicator rounded-2xl bg-blue-900 px-5 py-3 text-sm font-semibold text-white" data-step-indicator="1">1. Data Anak</button>
                        <button type="button" class="step-indicator rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-500 ring-1 ring-slate-200" data-step-indicator="2">2. Data Orang Tua / Wali</button>
                        <button type="button" class="step-indicator rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-500 ring-1 ring-slate-200" data-step-indicator="3">3. Upload Berkas</button>
                    </div>

                    @if (session('status'))
                        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                            <p class="font-semibold">Periksa kembali data berikut:</p>
                            <ul class="mt-2 list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="registrationForm" action="{{ route('data-diri.update') }}" method="POST" enctype="multipart/form-data" class="mt-8 rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                        @csrf
                        <input type="hidden" name="active_step" id="activeStepInput" value="{{ old('active_step', $errors->any() ? 2 : 1) }}">

                        <section class="form-step" data-step="1">
                            <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-4">
                                <div>
                                    <h2 class="text-2xl font-bold text-blue-950">A. Data Anak</h2>
                                    <p class="mt-1 text-sm text-slate-500">Isi identitas peserta didik dengan lengkap dan sesuai dokumen resmi.</p>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="full_name" class="text-sm font-medium text-slate-600">Nama Lengkap</label>
                                    <input id="full_name" name="full_name" type="text" required value="{{ old('full_name', $registration?->full_name) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none" placeholder="Masukkan nama lengkap anak">
                                </div>
                                <div>
                                    <label for="nickname" class="text-sm font-medium text-slate-600">Nama Panggilan</label>
                                    <input id="nickname" name="nickname" type="text" required value="{{ old('nickname', $registration?->nickname) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none" placeholder="Masukkan nama panggilan">
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div>
                                    <label for="gender" class="text-sm font-medium text-slate-600">Jenis Kelamin</label>
                                    <select id="gender" name="gender" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none">
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="Laki-laki" @selected(old('gender', $registration?->gender) === 'Laki-laki')>Laki-laki</option>
                                        <option value="Perempuan" @selected(old('gender', $registration?->gender) === 'Perempuan')>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="birth_place" class="text-sm font-medium text-slate-600">Tempat Lahir</label>
                                    <input id="birth_place" name="birth_place" type="text" required value="{{ old('birth_place', $registration?->birth_place) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none" placeholder="Masukkan tempat lahir">
                                </div>
                                <div>
                                    <label for="birth_date" class="text-sm font-medium text-slate-600">Tanggal Lahir</label>
                                    <input id="birth_date" name="birth_date" type="date" required value="{{ old('birth_date', $registration?->birth_date?->format('Y-m-d')) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none">
                                </div>
                                <div>
                                    <label for="origin_region" class="text-sm font-medium text-slate-600">Asal Daerah</label>
                                    <input id="origin_region" name="origin_region" type="text" required value="{{ old('origin_region', $registration?->origin_region) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none" placeholder="Masukkan asal daerah">
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div>
                                    <label for="weight_kg" class="text-sm font-medium text-slate-600">Berat Badan (kg)</label>
                                    <input id="weight_kg" name="weight_kg" type="number" step="0.01" min="0" required value="{{ old('weight_kg', $registration?->weight_kg) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none" placeholder="Contoh: 18">
                                </div>
                                <div>
                                    <label for="height_cm" class="text-sm font-medium text-slate-600">Tinggi Badan (cm)</label>
                                    <input id="height_cm" name="height_cm" type="number" step="0.01" min="0" required value="{{ old('height_cm', $registration?->height_cm) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none" placeholder="Contoh: 105">
                                </div>
                                <div>
                                    <label for="citizenship" class="text-sm font-medium text-slate-600">Kewarganegaraan</label>
                                    <select id="citizenship" name="citizenship" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none">
                                        <option value="">Pilih kewarganegaraan</option>
                                        <option value="WNI" @selected(old('citizenship', $registration?->citizenship) === 'WNI')>WNI</option>
                                        <option value="WNA" @selected(old('citizenship', $registration?->citizenship) === 'WNA')>WNA</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="special_needs" class="text-sm font-medium text-slate-600">Berkebutuhan Khusus</label>
                                    <select id="special_needs" name="special_needs" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none">
                                        <option value="">Pilih opsi</option>
                                        <option value="Ya" @selected(old('special_needs', $registration ? ($registration->special_needs ? 'Ya' : 'Tidak') : '') === 'Ya')>Ya</option>
                                        <option value="Tidak" @selected(old('special_needs', $registration ? ($registration->special_needs ? 'Ya' : 'Tidak') : '') === 'Tidak')>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                <div>
                                    <label for="child_order" class="text-sm font-medium text-slate-600">Anak ke-</label>
                                    <input id="child_order" name="child_order" type="number" min="1" required value="{{ old('child_order', $registration?->child_order) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none" placeholder="Contoh: 2">
                                </div>
                                <div>
                                    <label for="siblings_total" class="text-sm font-medium text-slate-600">Dari Total Anak</label>
                                    <input id="siblings_total" name="siblings_total" type="number" min="1" required value="{{ old('siblings_total', $registration?->siblings_total) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none" placeholder="Contoh: 3">
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                                <div>
                                    <label for="home_address" class="text-sm font-medium text-slate-600">Alamat Rumah</label>
                                    <textarea id="home_address" name="home_address" required class="mt-2 h-32 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none" placeholder="Masukkan alamat rumah lengkap">{{ old('home_address', $registration?->home_address) }}</textarea>
                                </div>
                                <div>
                                    <label for="medical_history" class="text-sm font-medium text-slate-600">Penyakit Bawaan yang Pernah Diderita</label>
                                    <textarea id="medical_history" name="medical_history" class="mt-2 h-32 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none" placeholder="Kosongkan jika tidak ada">{{ old('medical_history', $registration?->medical_history) }}</textarea>
                                </div>
                            </div>

                            <div class="mt-10 flex justify-end">
                                <button type="button" class="rounded-2xl bg-blue-900 px-6 py-3 font-semibold text-white transition hover:bg-blue-800" data-next-step="2">Lanjut</button>
                            </div>
                        </section>

                        <section class="form-step hidden" data-step="2">
                            <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-4">
                                <div>
                                    <h2 class="text-2xl font-bold text-blue-950">B. Data Orang Tua / Wali</h2>
                                    <p class="mt-1 text-sm text-slate-500">Isi data ayah dan ibu dengan lengkap sesuai informasi yang aktif digunakan.</p>
                                </div>
                            </div>

                            <div class="mt-6 rounded-2xl border border-fuchsia-100 bg-fuchsia-50 px-5 py-4">
                                <h3 class="text-xl font-bold text-blue-950">Data Ayah</h3>
                                <p class="mt-1 text-sm text-slate-500">Lengkapi identitas ayah atau wali utama yang bertanggung jawab.</p>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div>
                                    <label for="father_name" class="text-sm font-medium text-slate-600">Nama Ayah</label>
                                    <input id="father_name" name="father_name" type="text" required value="{{ old('father_name', $registration?->father_name) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-fuchsia-400 focus:outline-none" placeholder="Masukkan nama lengkap ayah">
                                </div>
                                <div>
                                    <label for="father_birth_info" class="text-sm font-medium text-slate-600">Tempat dan Tanggal Lahir</label>
                                    <input id="father_birth_info" name="father_birth_info" type="text" required value="{{ old('father_birth_info', $registration?->father_birth_info) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-fuchsia-400 focus:outline-none" placeholder="Contoh: Jakarta, 15 Januari 1985">
                                </div>
                                <div>
                                    <label for="father_job" class="text-sm font-medium text-slate-600">Pekerjaan</label>
                                    <input id="father_job" name="father_job" type="text" required value="{{ old('father_job', $registration?->father_job) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-fuchsia-400 focus:outline-none" placeholder="Masukkan pekerjaan ayah">
                                </div>
                                <div>
                                    <label for="father_education" class="text-sm font-medium text-slate-600">Pendidikan Terakhir</label>
                                    <select id="father_education" name="father_education" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-fuchsia-400 focus:outline-none">
                                        <option value="">Pilih pendidikan terakhir</option>
                                        @foreach (['SD / MI', 'SMP / MTs', 'SMA / SMK / MA', 'Diploma', 'S1', 'S2', 'S3'] as $option)
                                            <option value="{{ $option }}" @selected(old('father_education', $registration?->father_education) === $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="father_income" class="text-sm font-medium text-slate-600">Penghasilan</label>
                                    <select id="father_income" name="father_income" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-fuchsia-400 focus:outline-none">
                                        <option value="">Pilih rentang penghasilan</option>
                                        @foreach (['Kurang dari Rp 1.000.000', 'Rp 1.000.000 - Rp 3.000.000', 'Rp 3.000.001 - Rp 5.000.000', 'Rp 5.000.001 - Rp 10.000.000', 'Lebih dari Rp 10.000.000'] as $option)
                                            <option value="{{ $option }}" @selected(old('father_income', $registration?->father_income) === $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="father_phone" class="text-sm font-medium text-slate-600">No. HP</label>
                                    <input id="father_phone" name="father_phone" type="text" required value="{{ old('father_phone', $registration?->father_phone) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-fuchsia-400 focus:outline-none" placeholder="Contoh: 08123456789">
                                </div>
                            </div>

                            <div class="mt-10 rounded-2xl border border-fuchsia-100 bg-fuchsia-50 px-5 py-4">
                                <h3 class="text-xl font-bold text-blue-950">Data Ibu</h3>
                                <p class="mt-1 text-sm text-slate-500">Lengkapi identitas ibu atau wali pendamping dengan format yang sama.</p>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div>
                                    <label for="mother_name" class="text-sm font-medium text-slate-600">Nama Ibu</label>
                                    <input id="mother_name" name="mother_name" type="text" required value="{{ old('mother_name', $registration?->mother_name) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-fuchsia-400 focus:outline-none" placeholder="Masukkan nama lengkap ibu">
                                </div>
                                <div>
                                    <label for="mother_birth_info" class="text-sm font-medium text-slate-600">Tempat dan Tanggal Lahir</label>
                                    <input id="mother_birth_info" name="mother_birth_info" type="text" required value="{{ old('mother_birth_info', $registration?->mother_birth_info) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-fuchsia-400 focus:outline-none" placeholder="Contoh: Bandung, 20 Februari 1987">
                                </div>
                                <div>
                                    <label for="mother_job" class="text-sm font-medium text-slate-600">Pekerjaan</label>
                                    <input id="mother_job" name="mother_job" type="text" required value="{{ old('mother_job', $registration?->mother_job) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-fuchsia-400 focus:outline-none" placeholder="Masukkan pekerjaan ibu">
                                </div>
                                <div>
                                    <label for="mother_education" class="text-sm font-medium text-slate-600">Pendidikan Terakhir</label>
                                    <select id="mother_education" name="mother_education" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-fuchsia-400 focus:outline-none">
                                        <option value="">Pilih pendidikan terakhir</option>
                                        @foreach (['SD / MI', 'SMP / MTs', 'SMA / SMK / MA', 'Diploma', 'S1', 'S2', 'S3'] as $option)
                                            <option value="{{ $option }}" @selected(old('mother_education', $registration?->mother_education) === $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="mother_income" class="text-sm font-medium text-slate-600">Penghasilan</label>
                                    <select id="mother_income" name="mother_income" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-fuchsia-400 focus:outline-none">
                                        <option value="">Pilih rentang penghasilan</option>
                                        @foreach (['Kurang dari Rp 1.000.000', 'Rp 1.000.000 - Rp 3.000.000', 'Rp 3.000.001 - Rp 5.000.000', 'Rp 5.000.001 - Rp 10.000.000', 'Lebih dari Rp 10.000.000'] as $option)
                                            <option value="{{ $option }}" @selected(old('mother_income', $registration?->mother_income) === $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="mother_phone" class="text-sm font-medium text-slate-600">No. HP</label>
                                    <input id="mother_phone" name="mother_phone" type="text" required value="{{ old('mother_phone', $registration?->mother_phone) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-fuchsia-400 focus:outline-none" placeholder="Contoh: 08123456789">
                                </div>
                            </div>

                            <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:justify-between">
                                <button type="button" class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-600 transition hover:bg-slate-50" data-prev-step="1">Kembali</button>
                                <button type="button" class="rounded-2xl bg-blue-900 px-6 py-3 font-semibold text-white transition hover:bg-blue-800" data-next-step="3">Lanjut</button>
                            </div>
                        </section>

                        <section class="form-step hidden" data-step="3">
                            <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-4">
                                <div>
                                    <h2 class="text-2xl font-bold text-blue-950">C. Upload Berkas</h2>
                                    <p class="mt-1 text-sm text-slate-500">Upload dokumen penting dalam format JPG, PNG, atau PDF maksimal 4 MB.</p>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="child_photo" class="text-sm font-medium text-slate-600">Pas Foto 3x4 Anak</label>
                                    <input id="child_photo" name="child_photo" type="file" accept=".jpg,.jpeg,.png,.pdf" class="file-upload mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none">
                                    <p class="file-name-preview mt-2 text-sm text-slate-500" data-existing="{{ $registration?->child_photo_path ? basename($registration->child_photo_path) : 'Belum ada file dipilih' }}">{{ $registration?->child_photo_path ? basename($registration->child_photo_path) : 'Belum ada file dipilih' }}</p>
                                    @if ($registration?->child_photo_path)
                                        <a href="{{ asset('storage/' . $registration->child_photo_path) }}" target="_blank" class="mt-2 inline-flex text-sm font-semibold text-blue-700 hover:underline">Lihat file tersimpan</a>
                                    @endif
                                </div>
                                <div>
                                    <label for="parents_id_card" class="text-sm font-medium text-slate-600">Scan KTP Kedua Orang Tua</label>
                                    <input id="parents_id_card" name="parents_id_card" type="file" accept=".jpg,.jpeg,.png,.pdf" class="file-upload mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none">
                                    <p class="file-name-preview mt-2 text-sm text-slate-500" data-existing="{{ $registration?->parents_id_card_path ? basename($registration->parents_id_card_path) : 'Belum ada file dipilih' }}">{{ $registration?->parents_id_card_path ? basename($registration->parents_id_card_path) : 'Belum ada file dipilih' }}</p>
                                    @if ($registration?->parents_id_card_path)
                                        <a href="{{ asset('storage/' . $registration->parents_id_card_path) }}" target="_blank" class="mt-2 inline-flex text-sm font-semibold text-blue-700 hover:underline">Lihat file tersimpan</a>
                                    @endif
                                </div>
                                <div>
                                    <label for="birth_certificate" class="text-sm font-medium text-slate-600">Akte Lahir Anak</label>
                                    <input id="birth_certificate" name="birth_certificate" type="file" accept=".jpg,.jpeg,.png,.pdf" class="file-upload mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none">
                                    <p class="file-name-preview mt-2 text-sm text-slate-500" data-existing="{{ $registration?->birth_certificate_path ? basename($registration->birth_certificate_path) : 'Belum ada file dipilih' }}">{{ $registration?->birth_certificate_path ? basename($registration->birth_certificate_path) : 'Belum ada file dipilih' }}</p>
                                    @if ($registration?->birth_certificate_path)
                                        <a href="{{ asset('storage/' . $registration->birth_certificate_path) }}" target="_blank" class="mt-2 inline-flex text-sm font-semibold text-blue-700 hover:underline">Lihat file tersimpan</a>
                                    @endif
                                </div>
                                <div>
                                    <label for="family_card" class="text-sm font-medium text-slate-600">Kartu Keluarga</label>
                                    <input id="family_card" name="family_card" type="file" accept=".jpg,.jpeg,.png,.pdf" class="file-upload mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-blue-400 focus:outline-none">
                                    <p class="file-name-preview mt-2 text-sm text-slate-500" data-existing="{{ $registration?->family_card_path ? basename($registration->family_card_path) : 'Belum ada file dipilih' }}">{{ $registration?->family_card_path ? basename($registration->family_card_path) : 'Belum ada file dipilih' }}</p>
                                    @if ($registration?->family_card_path)
                                        <a href="{{ asset('storage/' . $registration->family_card_path) }}" target="_blank" class="mt-2 inline-flex text-sm font-semibold text-blue-700 hover:underline">Lihat file tersimpan</a>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-8 rounded-[1.75rem] border-l-4 border-sky-400 bg-slate-50 px-5 py-5 shadow-sm">
                                <label for="agreement" class="flex cursor-pointer items-start gap-4">
                                    <input
                                        id="agreement"
                                        name="agreement"
                                        type="checkbox"
                                        value="1"
                                        @checked(old('agreement'))
                                        class="mt-1 h-5 w-5 rounded border-slate-300 text-blue-700 focus:ring-blue-500"
                                    >
                                    <span class="text-base leading-8 text-slate-700">
                                        <strong>Ya, saya setuju</strong> bahwa seluruh data yang saya isikan dan/atau unggah adalah benar, sah, legal dan sesuai dengan keadaan dan kenyataan yang sesungguhnya.
                                    </span>
                                </label>
                            </div>

                            <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <button type="button" class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-600 transition hover:bg-slate-50" data-prev-step="2">Kembali</button>
                                <div class="flex flex-col gap-3 sm:flex-row">
                                    <button type="submit" name="action" value="submit" id="submitRegistrationButton" class="rounded-full bg-gradient-to-r from-indigo-500 to-purple-700 px-8 py-4 text-base font-extrabold uppercase tracking-wide text-white shadow-[0_18px_35px_rgba(79,70,229,0.25)] transition hover:opacity-95">
                                        Ya, Saya Mendaftar
                                    </button>
                                    <button type="submit" name="action" value="save" id="saveDraftButton" class="rounded-full bg-gradient-to-r from-amber-100 to-orange-300 px-8 py-4 text-base font-extrabold uppercase tracking-wide text-amber-900 shadow-[0_18px_35px_rgba(251,146,60,0.2)] transition hover:opacity-95">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </section>
                    </form>
                </div>
            </main>

            @include('dashboard.panel-ortu.partials.footer')
        </div>
    </div>

    <script>
        const formSteps = document.querySelectorAll('.form-step');
        const stepIndicators = document.querySelectorAll('[data-step-indicator]');
        const nextStepButtons = document.querySelectorAll('[data-next-step]');
        const prevStepButtons = document.querySelectorAll('[data-prev-step]');
        const activeStepInput = document.getElementById('activeStepInput');
        const registrationForm = document.getElementById('registrationForm');
        const submitRegistrationButton = document.getElementById('submitRegistrationButton');
        const saveDraftButton = document.getElementById('saveDraftButton');

        function renderStep(step) {
            activeStepInput.value = step;

            formSteps.forEach((section) => {
                section.classList.toggle('hidden', Number(section.dataset.step) !== step);
            });

            stepIndicators.forEach((indicator) => {
                const indicatorStep = Number(indicator.dataset.stepIndicator);
                indicator.className = 'step-indicator rounded-2xl px-5 py-3 text-sm font-semibold';

                if (indicatorStep === step) {
                    indicator.classList.add('bg-blue-900', 'text-white');
                } else if (indicatorStep < step) {
                    indicator.classList.add('bg-emerald-100', 'text-emerald-700', 'ring-1', 'ring-emerald-200');
                } else {
                    indicator.classList.add('bg-white', 'text-slate-500', 'ring-1', 'ring-slate-200');
                }
            });
        }

        nextStepButtons.forEach((button) => {
            button.addEventListener('click', () => {
                renderStep(Number(button.dataset.nextStep));
            });
        });

        stepIndicators.forEach((indicator) => {
            indicator.addEventListener('click', () => {
                renderStep(Number(indicator.dataset.stepIndicator));
            });
        });

        prevStepButtons.forEach((button) => {
            button.addEventListener('click', () => {
                renderStep(Number(button.dataset.prevStep));
            });
        });

        document.querySelectorAll('.file-upload').forEach((input) => {
            input.addEventListener('change', () => {
                const preview = input.parentElement.querySelector('.file-name-preview');

                if (!preview) {
                    return;
                }

                preview.textContent = input.files && input.files.length > 0
                    ? input.files[0].name
                    : (preview.dataset.existing || 'Belum ada file dipilih');
            });
        });

        registrationForm?.addEventListener('submit', () => {
            if (!submitRegistrationButton || !saveDraftButton) {
                return;
            }

            submitRegistrationButton.disabled = true;
            saveDraftButton.disabled = true;

            if (document.activeElement === saveDraftButton) {
                saveDraftButton.textContent = 'Menyimpan...';
                saveDraftButton.classList.add('opacity-70', 'cursor-not-allowed');
            } else {
                submitRegistrationButton.textContent = 'Menyimpan...';
                submitRegistrationButton.classList.add('opacity-70', 'cursor-not-allowed');
            }
        });

        @if ($errors->any())
            window.scrollTo({ top: 0, behavior: 'smooth' });
        @endif

        renderStep(Number(activeStepInput.value || 1));
    </script>
</body>
</html>
