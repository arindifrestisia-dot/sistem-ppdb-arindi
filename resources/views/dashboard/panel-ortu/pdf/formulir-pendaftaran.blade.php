<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pendaftaran</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 12px; }
        .title { text-align: center; margin-bottom: 20px; }
        .title h1 { margin: 0; font-size: 24px; }
        .title p { margin: 6px 0 0; color: #475569; }
        .card { border: 1px solid #cbd5e1; border-radius: 12px; padding: 14px; margin-bottom: 18px; }
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 10px; color: #1d4ed8; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 7px 0; vertical-align: top; border-bottom: 1px solid #e2e8f0; }
        td:first-child { width: 34%; font-weight: bold; }
    </style>
</head>
<body>
    <div class="title">
        <h1>Formulir Pendaftaran PPDB</h1>
        <p>RA Fadhilah - Nomor Registrasi {{ $registration->registration_number }}</p>
    </div>

    <div class="card">
        <div class="section-title">Data Anak</div>
        <table>
            <tr><td>Nama Lengkap</td><td>{{ $registration->full_name }}</td></tr>
            <tr><td>Nama Panggilan</td><td>{{ $registration->nickname }}</td></tr>
            <tr><td>Jenis Kelamin</td><td>{{ $registration->gender }}</td></tr>
            <tr><td>Tempat Lahir</td><td>{{ $registration->birth_place }}</td></tr>
            <tr><td>Tanggal Lahir</td><td>{{ optional($registration->birth_date)->format('d F Y') }}</td></tr>
            <tr><td>Agama</td><td>{{ $registration->religion }}</td></tr>
            <tr><td>Berat Badan</td><td>{{ $registration->weight_kg }} kg</td></tr>
            <tr><td>Tinggi Badan</td><td>{{ $registration->height_cm }} cm</td></tr>
            <tr><td>Alamat Rumah (Lengkap)</td><td>{{ $registration->home_address }}</td></tr>
            <tr><td>Asal Daerah</td><td>{{ $registration->origin_region }}</td></tr>
            <tr><td>Kewarganegaraan</td><td>{{ $registration->citizenship }}</td></tr>
            <tr><td>Berkebutuhan Khusus</td><td>{{ $registration->special_needs ? 'Ya' : 'Tidak' }}</td></tr>
            <tr><td>Keterangan Kebutuhan Khusus</td><td>{{ $registration->special_needs_description ?: '-' }}</td></tr>
            <tr><td>Status Anak</td><td>{{ $registration->child_status }}</td></tr>
            <tr><td>Golongan Darah</td><td>{{ $registration->blood_type }}</td></tr>
            <tr><td>Anak ke-</td><td>{{ $registration->child_order }}</td></tr>
            <tr><td>Dari Total Anak</td><td>{{ $registration->siblings_total }}</td></tr>
            <tr><td>Penyakit Bawaan</td><td>{{ $registration->medical_history ?: '-' }}</td></tr>
        </table>
    </div>

    <div class="card">
        <div class="section-title">Data Ayah</div>
        <table>
            <tr><td>Nama Ayah</td><td>{{ $registration->father_name }}</td></tr>
            <tr><td>Tempat/Tanggal Lahir</td><td>{{ $registration->father_birth_info }}</td></tr>
            <tr><td>Agama</td><td>{{ $registration->father_religion }}</td></tr>
            <tr><td>Kewarganegaraan</td><td>{{ $registration->father_citizenship }}</td></tr>
            <tr><td>Status Ayah</td><td>{{ $registration->father_status }}</td></tr>
            <tr><td>Pekerjaan</td><td>{{ $registration->father_job }}</td></tr>
            <tr><td>Pendidikan</td><td>{{ $registration->father_education }}</td></tr>
            <tr><td>Penghasilan</td><td>{{ $registration->father_income }}</td></tr>
            <tr><td>No. HP</td><td>{{ $registration->father_phone }}</td></tr>
            <tr><td>Alamat Lengkap</td><td>{{ $registration->father_address }}</td></tr>
            <tr><td>Email</td><td>{{ $registration->father_email ?: '-' }}</td></tr>
        </table>
    </div>

    <div class="card">
        <div class="section-title">Data Ibu</div>
        <table>
            <tr><td>Nama Ibu</td><td>{{ $registration->mother_name }}</td></tr>
            <tr><td>Tempat/Tanggal Lahir</td><td>{{ $registration->mother_birth_info }}</td></tr>
            <tr><td>Agama</td><td>{{ $registration->mother_religion }}</td></tr>
            <tr><td>Kewarganegaraan</td><td>{{ $registration->mother_citizenship }}</td></tr>
            <tr><td>Status Ibu</td><td>{{ $registration->mother_status }}</td></tr>
            <tr><td>Pekerjaan</td><td>{{ $registration->mother_job }}</td></tr>
            <tr><td>Pendidikan</td><td>{{ $registration->mother_education }}</td></tr>
            <tr><td>Penghasilan</td><td>{{ $registration->mother_income }}</td></tr>
            <tr><td>No. HP</td><td>{{ $registration->mother_phone }}</td></tr>
            <tr><td>Alamat Lengkap</td><td>{{ $registration->mother_address }}</td></tr>
            <tr><td>Email</td><td>{{ $registration->mother_email ?: '-' }}</td></tr>
        </table>
    </div>
</body>
</html>
