<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Bukti Pendaftaran</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; padding: 24px; }
        .card { border: 2px solid #60a5fa; border-radius: 20px; padding: 30px; }
        .center { text-align: center; }
        .badge { display: inline-block; padding: 8px 14px; border-radius: 999px; font-size: 12px; font-weight: bold; margin: 4px; }
        .green { background: #dcfce7; color: #15803d; }
        .blue { background: #dbeafe; color: #1d4ed8; }
        .number-box { background: #f8fafc; border-radius: 16px; padding: 18px; margin: 22px auto; width: 70%; }
        h1 { margin-bottom: 8px; }
        p { margin: 6px 0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="center">
            <div style="font-size:48px; color:#16a34a;">✓</div>
            <h1>Pendaftaran Berhasil Dikirim!</h1>
            <p>Formulir dan berkas Anda telah diterima sistem PPDB.</p>
            <p>Simpan nomor registrasi berikut sebagai bukti pendaftaran.</p>

            <div class="number-box">
                <p style="font-size: 14px; color:#475569;">Nomor Registrasi</p>
                <p style="font-size: 28px; font-weight: 800; letter-spacing: 4px;">{{ $registration->registration_number }}</p>
            </div>

            <div>
                <span class="badge green">Formulir Aktif</span>
                <span class="badge green">Pembayaran Lunas</span>
                <span class="badge blue">Menunggu Verifikasi Berkas</span>
            </div>
        </div>

        <div style="margin-top: 30px; background:#fafaf9; border-radius:16px; padding:20px;">
            <h3 style="margin-top:0;">Langkah selanjutnya:</h3>
            <ol style="padding-left: 20px; line-height: 1.8;">
                <li>Pantau status verifikasi berkas di portal PPDB.</li>
                <li>Pengumuman penerimaan akan diinformasikan oleh sekolah.</li>
                <li>Siapkan proses daftar ulang jika dinyatakan diterima.</li>
                <li>Hubungi sekolah jika ada pertanyaan: (0761) 27483</li>
            </ol>
        </div>
    </div>
</body>
</html>
