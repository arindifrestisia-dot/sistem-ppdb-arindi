<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Biodata Siswa RA Fadhilah</title>
    <style>
        @page { margin: 16px 18px; }
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 9.5px; }
        .sheet { border: 1.5px solid #1f2937; padding: 0 0 12px; }
        .topbar { background: #facc15; height: 10px; }
        .header { border-bottom: 1px solid #1f2937; padding: 7px 12px 6px; }
        .header-table { width: 100%; border-collapse: collapse; }
        .logo { width: 52px; height: 52px; object-fit: contain; }
        .school-name { margin: 0; color: #075985; font-family: DejaVu Serif, serif; font-size: 24px; font-weight: 700; line-height: 1; }
        .school-subtitle { margin: 2px 0 0; color: #0f172a; font-size: 10px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; }
        .contact { margin-top: 4px; font-size: 8.5px; color: #1f2937; }
        .brand-ribbon { background: #67d4ee; color: #0f3f78; font-size: 10px; font-weight: 700; letter-spacing: 6px; padding: 5px 0; text-align: center; text-transform: uppercase; }
        .intro { padding: 9px 12px 0; }
        .meta { width: 100%; border-collapse: collapse; }
        .number-box { border: 1.4px solid #111827; border-radius: 9px; padding: 6px; text-align: center; width: 120px; }
        .number-box .label { font-size: 8px; font-weight: 700; }
        .number-box .value { margin-top: 7px; font-size: 9.5px; font-weight: 700; }
        .student-photo { border: 1px solid #94a3b8; height: 92px; object-fit: cover; width: 78px; }
        .photo-placeholder { border: 1px solid #94a3b8; color: #64748b; display: inline-block; font-size: 8px; height: 92px; line-height: 92px; text-align: center; width: 78px; }
        .section-grid { padding: 7px 12px 0; }
        .columns { width: 100%; border-collapse: collapse; }
        .columns > tbody > tr > td { vertical-align: top; width: 50%; }
        .columns > tbody > tr > td:first-child { padding-right: 8px; }
        .columns > tbody > tr > td:last-child { padding-left: 8px; }
        .section-title { background: #67d4ee; border-radius: 4px; color: #0f3f78; display: inline-block; font-size: 10px; font-weight: 700; letter-spacing: 1px; margin: 0 0 4px; padding: 4px 22px; text-transform: uppercase; }
        .field-table { width: 100%; border-collapse: collapse; }
        .field-table td { padding: 2.4px 0; vertical-align: top; }
        .field-table .mark { color: #111827; width: 12px; }
        .field-table .label { font-weight: 700; width: 112px; }
        .field-table .colon { width: 8px; }
        .field-table .value { border-bottom: 1px solid #6b7280; min-height: 12px; }
        .field-table .short { width: 72px; }
        .footer { margin: 8px 12px 0; background: #67d4ee; color: #0f3f78; font-size: 10px; font-weight: 700; letter-spacing: 3px; padding: 5px; text-align: center; }
        .muted { color: #64748b; }
    </style>
</head>
<body>
    @php
        $logoRaPath = public_path('image/logo_RA.png');
        $photoPath = $registration->child_photo_path ? public_path('storage/' . $registration->child_photo_path) : null;
        $hasPhoto = $photoPath && file_exists($photoPath) && ! str_ends_with(strtolower($photoPath), '.pdf');
        $date = fn ($value) => $value ? $value->translatedFormat('d F Y') : '-';
        $display = fn ($value) => filled($value) ? $value : '-';
        $childCustomFields = $customFields->get('child', collect());
        $parentCustomFields = $customFields->get('parent', collect());
        $customValue = fn ($field) => $display(data_get($registration->custom_form_data ?? [], $field->field_key));

        $childRows = [
            'Nama Lengkap' => $registration->full_name,
            'Nama Panggilan' => $registration->nickname,
            'Jenis Kelamin' => $registration->gender,
            'Tempat, Tanggal Lahir' => trim(($registration->birth_place ?: '-') . ', ' . $date($registration->birth_date)),
            'Agama' => $registration->religion,
            'Kewarganegaraan' => $registration->citizenship,
            'Anak Nomor ke' => $registration->child_order,
            'Jumlah Saudara Kandung' => $registration->siblings_total,
            'Berat Badan' => $registration->weight_kg ? $registration->weight_kg . ' kg' : null,
            'Tinggi Badan' => $registration->height_cm ? $registration->height_cm . ' cm' : null,
            'Golongan Darah' => $registration->blood_type,
            'Penyakit yang Pernah Diderita' => $registration->medical_history,
            'Berkebutuhan Khusus' => $registration->special_needs ? 'Ya' : 'Tidak',
            'Keterangan Kebutuhan Khusus' => $registration->special_needs_description,
            'Status Anak' => $registration->child_status,
            'Asal Daerah' => $registration->origin_region,
            'Alamat Tempat Tinggal' => $registration->home_address,
            'Kelas' => $classLabel,
            'Tahun Ajaran' => $academicYear,
            'Email Akun Orang Tua' => $registration->user?->email,
        ];

        $fatherRows = [
            'Nama Ayah Kandung' => $registration->father_name,
            'Tempat/Tanggal Lahir Ayah' => $registration->father_birth_info,
            'Agama Ayah' => $registration->father_religion,
            'Kewarganegaraan Ayah' => $registration->father_citizenship,
            'Status Ayah' => $registration->father_status,
            'Pendidikan Ayah' => $registration->father_education,
            'Pekerjaan Ayah' => $registration->father_job,
            'Penghasilan Ayah' => $registration->father_income,
            'No. HP Ayah' => $registration->father_phone,
            'Email Ayah' => $registration->father_email,
            'Alamat Ayah' => $registration->father_address,
        ];

        $motherRows = [
            'Nama Ibu Kandung' => $registration->mother_name,
            'Tempat/Tanggal Lahir Ibu' => $registration->mother_birth_info,
            'Agama Ibu' => $registration->mother_religion,
            'Kewarganegaraan Ibu' => $registration->mother_citizenship,
            'Status Ibu' => $registration->mother_status,
            'Pendidikan Ibu' => $registration->mother_education,
            'Pekerjaan Ibu' => $registration->mother_job,
            'Penghasilan Ibu' => $registration->mother_income,
            'No. HP Ibu' => $registration->mother_phone,
            'Email Ibu' => $registration->mother_email,
            'Alamat Ibu' => $registration->mother_address,
        ];
    @endphp

    <div class="sheet">
        <div class="topbar"></div>

        <div class="header">
            <table class="header-table">
                <tr>
                    <td style="width: 62px;">
                        @if (file_exists($logoRaPath))
                            <img src="{{ $logoRaPath }}" class="logo" alt="Logo RA Fadhilah">
                        @endif
                    </td>
                    <td>
                        <h1 class="school-name">RA Fadhilah</h1>
                        <p class="school-subtitle">Raudhatul Athfal Fadhilah Pekanbaru</p>
                        <div class="contact">
                            Alamat: CCV9+42C, Jl. Muhajirin, Sidomulyo Barat, Kec. Tuah Madani, Kota Pekanbaru, Riau 28294
                            | Telp: 0821 6207 736 | WA: 0822 8681 7315
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="brand-ribbon">Formulir Biodata Peserta Didik</div>

        <div class="intro">
            <table class="meta">
                <tr>
                    <td>
                        <div class="section-title">Foto Anak</div><br>
                        @if ($hasPhoto)
                            <img src="{{ $photoPath }}" class="student-photo" alt="Foto {{ $registration->full_name }}">
                        @else
                            <span class="photo-placeholder">Foto Anak</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div class="number-box">
                            <div class="label">No Pendaftaran</div>
                            <div class="value">{{ $registration->registration_number ?: '-' }}</div>
                            <div class="muted" style="margin-top: 5px; font-size: 7px;">diisi otomatis sistem</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section-grid">
            <div class="section-title">Data Diri Siswa</div>
            <table class="field-table">
                @foreach ($childRows as $label => $value)
                    <tr>
                        <td class="mark">o</td>
                        <td class="label">{{ $label }}</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $display($value) }}</td>
                    </tr>
                @endforeach
                @foreach ($childCustomFields as $field)
                    <tr>
                        <td class="mark">o</td>
                        <td class="label">{{ $field->label }}</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $customValue($field) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="section-grid">
            <table class="columns">
                <tr>
                    <td>
                        <div class="section-title">Data Ayah / Wali</div>
                        <table class="field-table">
                            @foreach ($fatherRows as $label => $value)
                                <tr>
                                    <td class="mark">o</td>
                                    <td class="label">{{ $label }}</td>
                                    <td class="colon">:</td>
                                    <td class="value">{{ $display($value) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                    <td>
                        <div class="section-title">Data Ibu / Wali</div>
                        <table class="field-table">
                            @foreach ($motherRows as $label => $value)
                                <tr>
                                    <td class="mark">o</td>
                                    <td class="label">{{ $label }}</td>
                                    <td class="colon">:</td>
                                    <td class="value">{{ $display($value) }}</td>
                                </tr>
                            @endforeach
                            @foreach ($parentCustomFields as $field)
                                <tr>
                                    <td class="mark">o</td>
                                    <td class="label">{{ $field->label }}</td>
                                    <td class="colon">:</td>
                                    <td class="value">{{ $customValue($field) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">Beradab, Ceria, Mandiri, dan Berakhlak Islami</div>
    </div>
</body>
</html>
