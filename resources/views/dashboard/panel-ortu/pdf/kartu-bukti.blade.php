<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Bukti Pendaftaran</title>
    <style>
        @page { margin: 18px; }
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 11px; }
        .page { border: 1.6px solid #1f2937; min-height: 760px; padding: 22px 24px; }
        .header { position: relative; text-align: center; min-height: 72px; }
        .logo { position: absolute; top: 0; width: 58px; height: 58px; object-fit: contain; }
        .logo-left { left: 0; }
        .logo-right { right: 0; }
        .title { margin: 0; font-family: DejaVu Serif, serif; font-size: 18px; font-weight: 700; line-height: 1.2; }
        .subtitle { margin: 2px 0 0; font-family: DejaVu Serif, serif; font-size: 15px; font-weight: 700; line-height: 1.25; }
        .box { border: 1px solid #4b5563; margin-top: 14px; padding: 10px; }
        .profile-table { width: 100%; border-collapse: collapse; }
        .profile-table td { padding: 3px 0; vertical-align: top; }
        .label { width: 142px; }
        .colon { width: 12px; text-align: center; }
        .student-photo { width: 118px; height: 142px; border: 1px solid #cbd5e1; object-fit: cover; }
        .photo-cell { width: 136px; text-align: right; }
        .photo-placeholder { width: 118px; height: 142px; border: 1px solid #cbd5e1; background: #f8fafc; text-align: center; color: #64748b; font-size: 10px; line-height: 142px; display: inline-block; }
        .section-table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        .section-table th { background: #05a96b; color: white; border: 1px solid #d1d5db; padding: 8px 7px; font-size: 10px; text-align: center; }
        .section-table td { border: 1px solid #d1d5db; padding: 7px; vertical-align: top; }
        .result { border: 1px solid #4b5563; margin-top: 16px; padding: 9px; font-weight: 700; }
        .info { border: 1px solid #4b5563; margin-top: 12px; padding: 8px; }
        .info-title { margin: 0 0 7px; font-weight: 700; font-size: 12px; }
        .info ol { margin: 0; padding-left: 18px; line-height: 1.55; }
        .verify { margin-top: 14px; }
        .qr { width: 100px; border-collapse: collapse; }
        .qr td { width: 5px; height: 5px; padding: 0; }
        .black { background: #111827; }
        .white { background: #ffffff; }
        .small { font-size: 9px; color: #475569; margin-top: 6px; }
    </style>
</head>
<body>
    @php
        $logoTkPath = public_path('image/logo_TK.png');
        $logoRaPath = public_path('image/logo_RA.png');
        $photoPath = $registration->child_photo_path ? public_path('storage/' . $registration->child_photo_path) : null;
        $hasPhoto = $photoPath && file_exists($photoPath) && ! str_ends_with(strtolower($photoPath), '.pdf');
        $birthDate = $registration->birth_date ? $registration->birth_date->format('d F Y') : '-';
        $submittedAt = $registration->submitted_at ? $registration->submitted_at->format('d-m-Y H:i') . ' WIB' : '-';
        $verificationLabel = match ($registration->verification_status) {
            'terverifikasi' => 'Terverifikasi',
            'revisi' => 'Perlu Revisi',
            'ditolak' => 'Ditolak',
            default => 'Proses Verifikasi',
        };
        $selectionLabel = match ($registration->selection_result) {
            'lulus' => 'Lulus',
            'tidak_lulus' => 'Tidak Lulus',
            default => 'Pengumuman Hasil Akhir Belum Tersedia',
        };
        $qrSeed = crc32(($registration->registration_number ?? '') . '|' . ($registration->full_name ?? ''));
    @endphp

    <div class="page">
        <div class="header">
            @if (file_exists($logoTkPath))
                <img src="{{ $logoTkPath }}" class="logo logo-left" alt="Logo TK">
            @endif

            @if (file_exists($logoRaPath))
                <img src="{{ $logoRaPath }}" class="logo logo-right" alt="Logo RA Fadhilah">
            @endif

            <h1 class="title">KARTU PENDAFTARAN</h1>
            <p class="subtitle">PPDB ONLINE RA FADHILAH</p>
            <p class="subtitle">TAHUN AJARAN {{ now()->format('Y') }}/{{ now()->addYear()->format('Y') }}</p>
        </div>

        <div class="box">
            <table class="profile-table">
                <tr>
                    <td>
                        <table class="profile-table">
                            <tr><td class="label">Nomor Pendaftaran</td><td class="colon">:</td><td>{{ $registration->registration_number }}</td></tr>
                            <tr><td class="label">Nama Peserta</td><td class="colon">:</td><td>{{ $registration->full_name }}</td></tr>
                            <tr><td class="label">Nama Panggilan</td><td class="colon">:</td><td>{{ $registration->nickname ?: '-' }}</td></tr>
                            <tr><td class="label">Tempat Lahir</td><td class="colon">:</td><td>{{ $registration->birth_place ?: '-' }}</td></tr>
                            <tr><td class="label">Tanggal Lahir</td><td class="colon">:</td><td>{{ $birthDate }}</td></tr>
                            <tr><td class="label">Jenis Kelamin</td><td class="colon">:</td><td>{{ $registration->gender ?: '-' }}</td></tr>
                            <tr><td class="label">Agama</td><td class="colon">:</td><td>{{ $registration->religion ?: '-' }}</td></tr>
                            <tr><td class="label">Nama Ayah</td><td class="colon">:</td><td>{{ $registration->father_name ?: '-' }}</td></tr>
                            <tr><td class="label">Nama Ibu</td><td class="colon">:</td><td>{{ $registration->mother_name ?: '-' }}</td></tr>
                            <tr><td class="label">Alamat</td><td class="colon">:</td><td>{{ $registration->home_address ?: '-' }}</td></tr>
                        </table>
                    </td>
                    <td class="photo-cell">
                        @if ($hasPhoto)
                            <img src="{{ $photoPath }}" class="student-photo" alt="Foto {{ $registration->full_name }}">
                        @else
                            <span class="photo-placeholder">Foto Anak</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <table class="section-table">
            <thead>
                <tr>
                    <th style="width: 24%;">PENDAFTARAN</th>
                    <th>PILIHAN SEKOLAH</th>
                    <th style="width: 28%;">STATUS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PPDB<br>{{ $submittedAt }}</td>
                    <td>RA Fadhilah<br>Taman Kanak-Kanak / Raudhatul Athfal</td>
                    <td>{{ $verificationLabel }}</td>
                </tr>
            </tbody>
        </table>

        <div class="result">
            HASIL AKHIR <span style="font-weight: 400;">:</span> {{ $selectionLabel }}
        </div>

        <div class="info">
            <p class="info-title">INFORMASI PENTING</p>
            <ol>
                <li>Kartu pendaftaran ini wajib dibawa dan ditunjukkan saat daftar ulang atau verifikasi sekolah.</li>
                <li>Membawa kartu/bukti identitas diri anak dan orang tua/wali.</li>
                <li>Membawa seluruh dokumen asli yang sudah diunggah pada formulir pendaftaran.</li>
                <li>Hubungi RA Fadhilah jika terdapat data yang perlu diperbaiki.</li>
            </ol>
        </div>

        <div class="verify">
            <table class="qr">
                @for ($row = 0; $row < 21; $row++)
                    <tr>
                        @for ($col = 0; $col < 21; $col++)
                            @php
                                $finder = ($row < 7 && $col < 7) || ($row < 7 && $col > 13) || ($row > 13 && $col < 7);
                                $finderInner = ($row > 1 && $row < 5 && $col > 1 && $col < 5)
                                    || ($row > 1 && $row < 5 && $col > 15 && $col < 19)
                                    || ($row > 15 && $row < 19 && $col > 1 && $col < 5);
                                $cellOn = $finder
                                    ? ($row === 0 || $row === 6 || $col === 0 || $col === 6 || $col === 14 || $col === 20 || $finderInner)
                                    : (($row * 31 + $col * 17 + $qrSeed) % 5 < 2);
                            @endphp
                            <td class="{{ $cellOn ? 'black' : 'white' }}"></td>
                        @endfor
                    </tr>
                @endfor
            </table>
            <div class="small">Kode verifikasi: {{ $registration->registration_number }}</div>
        </div>
    </div>
</body>
</html>
