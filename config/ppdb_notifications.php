<?php

return [
    'enabled' => env('PPDB_NOTIFICATIONS_ENABLED', true),

    'channels' => [
        'mail' => env('PPDB_NOTIFY_MAIL_ENABLED', true),
        'wablas' => env('PPDB_NOTIFY_WABLAS_ENABLED', true),
    ],

    'amounts' => [
        'form' => (int) env('PPDB_FORM_PAYMENT_AMOUNT', 150000),
        're_registration' => (int) env('PPDB_REREGISTRATION_AMOUNT', 1500000),
    ],

    'bank' => [
        'account_name' => env('PPDB_BANK_ACCOUNT_NAME', 'RA Fadhilah'),
        'account_number' => env('PPDB_BANK_ACCOUNT_NUMBER', '0000000000'),
        'bank_name' => env('PPDB_BANK_NAME', 'Bank Sekolah'),
    ],

    'deadlines' => [
        're_registration_days' => (int) env('PPDB_REREGISTRATION_DEADLINE_DAYS', 7),
    ],

    'footer' => env('PPDB_NOTIFICATION_FOOTER', 'RA Fadhilah'),

    'templates' => [
        'account_registered' => [
            'subject' => 'Registrasi Akun Berhasil',
            'message' => 'Registrasi akun PPDB RA Fadhilah berhasil. Silakan login dan lengkapi formulir pendaftaran Ananda.',
        ],
        'form_payment_instruction' => [
            'subject' => 'Instruksi Pembayaran Formulir',
            'message' => 'Silakan melakukan pembayaran formulir sebesar {form_amount} ke {bank_name} {bank_account_number} a.n. {bank_account_name}, lalu unggah bukti pembayaran.',
        ],
        'form_payment_proof_uploaded' => [
            'subject' => 'Bukti Pembayaran Berhasil Diunggah',
            'message' => 'Bukti pembayaran formulir berhasil diunggah dan menunggu verifikasi pihak panitia.',
        ],
        'form_payment_approved' => [
            'subject' => 'Pembayaran Formulir Disetujui',
            'message' => 'Pembayaran formulir sebesar {form_amount} telah diverifikasi dan dinyatakan berhasil.',
        ],
        'form_submitted' => [
            'subject' => 'Formulir Berhasil Dikirim',
            'message' => 'Formulir pendaftaran Ananda berhasil dikirim. Nomor registrasi Anda: {registration_number}.',
        ],
        'documents_uploaded' => [
            'subject' => 'Dokumen Berhasil Diunggah',
            'message' => 'Dokumen persyaratan pendaftaran Ananda berhasil diunggah.',
        ],
        'interview_schedule_selected' => [
            'subject' => 'Jadwal Wawancara Berhasil Dipilih',
            'message' => 'Jadwal wawancara Anda: {interview_date} pukul {interview_time}. {interview_room}.',
        ],
        'interview_reminder_h3' => [
            'subject' => 'Reminder Wawancara H-3',
            'message' => 'Pengingat: wawancara Ananda akan dilaksanakan 3 hari lagi pada {interview_date} pukul {interview_time}. {interview_room}.',
        ],
        'interview_reminder_h1' => [
            'subject' => 'Reminder Wawancara H-1',
            'message' => 'Pengingat: wawancara Ananda dilaksanakan besok pada {interview_date} pukul {interview_time}. {interview_room}.',
        ],
        'registration_processing' => [
            'subject' => 'Pendaftaran Sedang Diproses',
            'message' => 'Pendaftaran Ananda sedang diproses oleh panitia.',
        ],
        'registration_verified' => [
            'subject' => 'Pendaftaran Berhasil Diverifikasi',
            'message' => 'Data pendaftaran Ananda telah diverifikasi.',
        ],
        'selection_announced' => [
            'subject' => 'Hasil Seleksi Telah Diumumkan',
            'message' => 'Hasil seleksi telah tersedia. Silakan login untuk melihat hasil.',
        ],
        'selection_passed' => [
            'subject' => 'Hasil Seleksi - Lulus',
            'message' => 'Selamat, Ananda dinyatakan LULUS seleksi PPDB RA Fadhilah.',
        ],
        'selection_failed' => [
            'subject' => 'Hasil Seleksi - Tidak Lulus',
            'message' => 'Terima kasih telah mengikuti proses PPDB. Ananda belum dinyatakan lulus.',
        ],
        're_registration_instruction' => [
            'subject' => 'Instruksi Daftar Ulang',
            'message' => 'Silakan melakukan daftar ulang sebesar {re_registration_amount}. Rekening: {bank_name} {bank_account_number} a.n. {bank_account_name}. Deadline: {re_registration_deadline}.',
        ],
        're_registration_proof_uploaded' => [
            'subject' => 'Bukti Pembayaran Daftar Ulang Berhasil Diunggah',
            'message' => 'Bukti pembayaran daftar ulang berhasil diunggah dan menunggu verifikasi.',
        ],
        're_registration_approved' => [
            'subject' => 'Pembayaran Daftar Ulang Disetujui',
            'message' => 'Pembayaran daftar ulang telah diverifikasi.',
        ],
        're_registration_deadline_reminder' => [
            'subject' => 'Reminder Batas Waktu Daftar Ulang',
            'message' => 'Batas waktu daftar ulang tersisa {days_left} hari. Deadline: {re_registration_deadline}.',
        ],
        'student_officially_registered' => [
            'subject' => 'Siswa Resmi Terdaftar',
            'message' => 'Selamat, Ananda telah resmi terdaftar sebagai peserta didik Tahun Ajaran {academic_year}.',
        ],
        'early_school_activity_info' => [
            'subject' => 'Informasi Kegiatan Awal Sekolah',
            'message' => 'Informasi orientasi, pengambilan seragam, dan jadwal masuk telah tersedia. Silakan login atau hubungi panitia untuk detailnya.',
        ],
    ],
];
