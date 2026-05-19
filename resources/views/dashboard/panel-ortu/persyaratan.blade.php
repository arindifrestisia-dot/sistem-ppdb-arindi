<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persyaratan Pendaftaran</title>
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
        @php($activeMenu = 'persyaratan')
        @include('dashboard.panel-ortu.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('dashboard.panel-ortu.partials.topbar')

            <main class="flex-1 px-5 py-6 md:px-8">
                <div class="mx-auto max-w-6xl">
                    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                        <div>
                            <h1 class="text-3xl font-extrabold text-blue-950 md:text-5xl">Persyaratan Pendaftaran</h1>
                            <p class="mt-2 text-lg text-slate-500">Periksa kembali data anak, orang tua/wali, dan seluruh berkas yang sudah dikirim melalui formulir pendaftaran.</p>
                        </div>
                        @if ($registration->submitted_at)
                            <span class="inline-flex rounded-2xl bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">Formulir Sudah Dikirim</span>
                        @else
                            <span class="inline-flex rounded-2xl bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">Menunggu Pengiriman Formulir</span>
                        @endif
                    </div>

                    @if (session('status'))
                        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-8 space-y-6">
                        <section class="rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                            <div class="border-b border-slate-200 pb-4">
                                <h2 class="text-2xl font-bold text-blue-950">A. Review Data Anak</h2>
                                <p class="mt-1 text-sm text-slate-500">Pastikan seluruh data anak sudah benar sesuai dokumen resmi.</p>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div><p class="text-sm font-medium text-slate-500">Nama Lengkap</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->full_name ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Nama Panggilan</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->nickname ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Jenis Kelamin</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->gender ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Tempat Lahir</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->birth_place ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Tanggal Lahir</p><p class="mt-2 text-base font-semibold text-slate-800">{{ optional($registration->birth_date)->format('d M Y') ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Agama</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->religion ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Berat Badan</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->weight_kg ? $registration->weight_kg . ' kg' : '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Tinggi Badan</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->height_cm ? $registration->height_cm . ' cm' : '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Asal Daerah</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->origin_region ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Kewarganegaraan</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->citizenship ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Berkebutuhan Khusus</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->special_needs ? 'Ya' : 'Tidak' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Status Anak</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->child_status ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Golongan Darah</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->blood_type ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Anak ke-</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->child_order ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Dari Total Anak</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->siblings_total ?: '-' }}</p></div>
                            </div>

                            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Alamat Rumah (Lengkap)</p>
                                    <p class="mt-2 text-base leading-7 font-semibold text-slate-800">{{ $registration->home_address ?: '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Keterangan Kebutuhan Khusus</p>
                                    <p class="mt-2 text-base leading-7 font-semibold text-slate-800">{{ $registration->special_needs_description ?: '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Penyakit Bawaan yang Pernah Diderita</p>
                                    <p class="mt-2 text-base leading-7 font-semibold text-slate-800">{{ $registration->medical_history ?: '-' }}</p>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                            <div class="border-b border-slate-200 pb-4">
                                <h2 class="text-2xl font-bold text-blue-950">B. Review Data Orang Tua / Wali</h2>
                                <p class="mt-1 text-sm text-slate-500">Cek kembali data ayah dan ibu yang sudah tersimpan di formulir.</p>
                            </div>

                            <div class="mt-6 rounded-2xl border border-fuchsia-100 bg-fuchsia-50 px-5 py-4">
                                <h3 class="text-xl font-bold text-blue-950">Data Ayah</h3>
                            </div>
                            <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                <div><p class="text-sm font-medium text-slate-500">Nama Ayah</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->father_name ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Tempat dan Tanggal Lahir</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->father_birth_info ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Agama Ayah</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->father_religion ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Kewarganegaraan Ayah</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->father_citizenship ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Status Ayah</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->father_status ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Pekerjaan</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->father_job ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Pendidikan Terakhir</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->father_education ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Penghasilan</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->father_income ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">No. HP</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->father_phone ?: '-' }}</p></div>
                                <div class="xl:col-span-3"><p class="text-sm font-medium text-slate-500">Alamat Lengkap Ayah</p><p class="mt-2 text-base leading-7 font-semibold text-slate-800">{{ $registration->father_address ?: '-' }}</p></div>
                            </div>

                            <div class="mt-8 rounded-2xl border border-fuchsia-100 bg-fuchsia-50 px-5 py-4">
                                <h3 class="text-xl font-bold text-blue-950">Data Ibu</h3>
                            </div>
                            <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                <div><p class="text-sm font-medium text-slate-500">Nama Ibu</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->mother_name ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Tempat dan Tanggal Lahir</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->mother_birth_info ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Agama Ibu</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->mother_religion ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Kewarganegaraan Ibu</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->mother_citizenship ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Status Ibu</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->mother_status ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Pekerjaan</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->mother_job ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Pendidikan Terakhir</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->mother_education ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">Penghasilan</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->mother_income ?: '-' }}</p></div>
                                <div><p class="text-sm font-medium text-slate-500">No. HP</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $registration->mother_phone ?: '-' }}</p></div>
                                <div class="xl:col-span-3"><p class="text-sm font-medium text-slate-500">Alamat Lengkap Ibu</p><p class="mt-2 text-base leading-7 font-semibold text-slate-800">{{ $registration->mother_address ?: '-' }}</p></div>
                            </div>
                        </section>

                        <section class="rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                            <div class="border-b border-slate-200 pb-4">
                                <h2 class="text-2xl font-bold text-blue-950">C. Review Berkas Upload</h2>
                                <p class="mt-1 text-sm text-slate-500">Buka dan periksa seluruh dokumen yang sudah diunggah.</p>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2">
                                @foreach ([
                                    ['label' => 'Pas Foto 3x4 Anak', 'path' => $registration->child_photo_path],
                                    ['label' => 'Scan KTP Kedua Orang Tua', 'path' => $registration->parents_id_card_path],
                                    ['label' => 'Akte Lahir Anak', 'path' => $registration->birth_certificate_path],
                                    ['label' => 'Kartu Keluarga', 'path' => $registration->family_card_path],
                                ] as $document)
                                    <div class="rounded-2xl border border-slate-200 p-5">
                                        <p class="text-sm font-medium text-slate-500">{{ $document['label'] }}</p>
                                        <p class="mt-2 text-base font-semibold text-slate-800">{{ $document['path'] ? basename($document['path']) : 'Belum ada file' }}</p>
                                        @if ($document['path'])
                                            <a href="{{ asset('storage/' . $document['path']) }}" target="_blank" class="mt-4 inline-flex rounded-xl bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">
                                                Lihat Berkas
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <section class="rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                            @if ($registration->submitted_at)
                                <div class="rounded-[1.75rem] border border-emerald-200 bg-emerald-50 px-6 py-6">
                                    <h2 class="text-2xl font-bold text-emerald-800">Pendaftaran Sudah Dikonfirmasi</h2>
                                    <p class="mt-3 text-sm leading-7 text-emerald-700">Setelah orang tua mencentang persetujuan dan menekan tombol <strong>Ya, Saya Mendaftar</strong>, pendaftaran tidak perlu dikunci lagi. Anda bisa langsung melanjutkan ke pemilihan jadwal wawancara.</p>
                                </div>

                                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-end">
                                    <a href="{{ route('data-diri') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                        Lihat Formulir
                                    </a>
                                    <a href="{{ route('wawancara') }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-indigo-500 to-blue-800 px-8 py-4 text-base font-extrabold uppercase tracking-wide text-white shadow-[0_18px_35px_rgba(37,99,235,0.25)] transition hover:opacity-95">
                                        Pilih Jadwal Wawancara
                                    </a>
                                </div>
                            @else
                                <div class="rounded-[1.75rem] border border-amber-200 bg-amber-50 px-6 py-6">
                                    <h2 class="text-2xl font-bold text-amber-800">Formulir Belum Dikirim</h2>
                                    <p class="mt-3 text-sm leading-7 text-amber-700">Setelah orang tua mencentang persetujuan dan menekan tombol <strong>Ya, Saya Mendaftar</strong> pada formulir data diri, jadwal wawancara akan langsung tersedia tanpa perlu mengunci pendaftaran lagi.</p>
                                </div>

                                <div class="mt-8 flex justify-end">
                                    <a href="{{ route('data-diri') }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-indigo-500 to-purple-700 px-8 py-4 text-base font-extrabold uppercase tracking-wide text-white shadow-[0_18px_35px_rgba(79,70,229,0.25)] transition hover:opacity-95">
                                        Kembali ke Formulir
                                    </a>
                                </div>
                            @endif
                        </section>
                    </div>
                </div>
            </main>

            @include('dashboard.panel-ortu.partials.footer')
        </div>
    </div>
</body>
</html>
