<?php

return [
    'enabled' => env('PPDB_NOTIFICATIONS_ENABLED', true),

    'channels' => [
        'mail' => env('PPDB_NOTIFY_MAIL_ENABLED', true),
        'fonnte' => env('PPDB_NOTIFY_FONNTE_ENABLED', true),
    ],

    'amounts' => [
        'form' => (int) env('PPDB_FORM_PAYMENT_AMOUNT', 150000),
        're_registration' => (int) env('PPDB_REREGISTRATION_AMOUNT', 1550000),
    ],

    'bank' => [
        'account_name' => env('PPDB_BANK_ACCOUNT_NAME', 'RA Fadhilah'),
        'account_number' => env('PPDB_BANK_ACCOUNT_NUMBER', '0000000000'),
        'bank_name' => env('PPDB_BANK_NAME', 'Bank Sekolah'),
    ],

    'deadlines' => [
        're_registration_days' => (int) env('PPDB_REREGISTRATION_DEADLINE_DAYS', 28),
    ],

    'footer' => env('PPDB_NOTIFICATION_FOOTER', 'RA Fadhilah'),

    'templates' => [
        'account_registered' => [
            'subject' => 'Registrasi Akun Berhasil',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami informasikan bahwa akun PPDB Ananda telah berhasil dibuat pada Sistem PPDB RA Fadhilah.\n\nData akun tersebut kini dapat digunakan untuk masuk ke portal PPDB dan melanjutkan proses pendaftaran peserta didik baru. Silakan login ke sistem menggunakan email dan password yang telah didaftarkan.\n\nTerima kasih atas perhatian Anda.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'login_success' => [
            'subject' => 'Login Akun Berhasil',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami informasikan bahwa akun PPDB Ananda telah berhasil masuk ke Sistem PPDB RA Fadhilah.\n\nSilakan melanjutkan proses pendaftaran sesuai dengan tahapan yang tersedia pada portal orang tua.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'form_payment_instruction' => [
            'subject' => 'Instruksi Pembayaran Formulir',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nUntuk melanjutkan proses pendaftaran, Bapak/Ibu perlu melakukan pembayaran formulir PPDB dengan rincian sebagai berikut:\n\nJumlah Pembayaran: {form_amount}\n\nMetode Pembayaran: Midtrans, transfer BRI, DANA, atau tunai (cash) langsung ke sekolah.\n\nSetelah melakukan pembayaran, mohon unggah bukti transfer melalui menu Formulir pada Sistem PPDB RA Fadhilah.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'form_payment_proof_uploaded' => [
            'subject' => 'Bukti Pembayaran Berhasil Diunggah',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami informasikan bahwa bukti pembayaran formulir PPDB Ananda telah berhasil diunggah ke Sistem PPDB RA Fadhilah.\n\nSaat ini, pembayaran sedang menunggu proses verifikasi oleh panitia. Mohon kesediaannya menunggu hingga status pembayaran dinyatakan valid.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'form_payment_approved' => [
            'subject' => 'Pembayaran Formulir Disetujui',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami informasikan bahwa pembayaran formulir PPDB Ananda telah berhasil diverifikasi dan disetujui.\n\nStatus Pembayaran: Terverifikasi\n\nJumlah Pembayaran: {form_amount}\n\nSilakan melanjutkan proses pendaftaran dengan mengisi formulir data calon peserta didik pada Sistem PPDB RA Fadhilah.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'form_submitted' => [
            'subject' => 'Formulir Berhasil Dikirim',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami informasikan bahwa formulir pendaftaran Ananda telah berhasil dikirim melalui Sistem PPDB RA Fadhilah.\n\nNomor Pendaftaran: {registration_number}\n\nSilakan melanjutkan proses berikutnya dengan memilih jadwal wawancara yang tersedia pada portal PPDB.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'documents_uploaded' => [
            'subject' => 'Dokumen Berhasil Diunggah',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami informasikan bahwa dokumen persyaratan pendaftaran Ananda telah berhasil diunggah ke Sistem PPDB RA Fadhilah.\n\nDokumen akan segera diperiksa oleh panitia sebagai bagian dari proses verifikasi pendaftaran. Silakan pantau status pendaftaran secara berkala melalui portal orang tua.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'interview_schedule_selected' => [
            'subject' => 'Jadwal Wawancara Berhasil Dipilih',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami informasikan bahwa jadwal wawancara Ananda telah berhasil dikonfirmasi dengan rincian berikut:\n\nHari/Tanggal: {interview_date}\n\nWaktu: {interview_time} WIB\n\nRuangan: {interview_room}\n\nMohon hadir tepat waktu sesuai jadwal yang telah dipilih. Terima kasih atas perhatian dan kerja sama Bapak/Ibu.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'interview_reminder_h3' => [
            'subject' => 'Reminder Wawancara H-3',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami mengingatkan bahwa jadwal wawancara Ananda akan dilaksanakan dalam 3 hari lagi:\n\nHari/Tanggal: {interview_date}\n\nWaktu: {interview_time} WIB\n\nRuangan: {interview_room}\n\nMohon Bapak/Ibu mempersiapkan kehadiran Ananda sesuai dengan jadwal yang telah ditentukan. Terima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'interview_reminder_h1' => [
            'subject' => 'Reminder Wawancara H-1',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami mengingatkan bahwa jadwal wawancara Ananda akan dilaksanakan BESOK:\n\nHari/Tanggal: {interview_date}\n\nWaktu: {interview_time} WIB\n\nRuangan: {interview_room}\n\nMohon hadir tepat waktu dan membawa dokumen fisik yang diperlukan apabila diminta oleh panitia. Terima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'registration_processing' => [
            'subject' => 'Pendaftaran Sedang Diproses',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami informasikan bahwa data pendaftaran Ananda saat ini telah masuk ke tahap pemeriksaan oleh panitia.\n\nPanitia sedang melakukan pengecekan berkas, dokumen persyaratan, serta keselarasan data lainnya. Silakan pantau informasi terbaru secara berkala melalui Sistem PPDB RA Fadhilah.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'registration_verified' => [
            'subject' => 'Pendaftaran Berhasil Diverifikasi',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami informasikan bahwa data pendaftaran Ananda telah dinyatakan selesai diperiksa dan berhasil diverifikasi.\n\nStatus Pendaftaran: Terverifikasi\n\nSelanjutnya, Bapak/Ibu dapat memantau pengumuman hasil seleksi akhir melalui menu Status Lulus pada Sistem PPDB RA Fadhilah.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'selection_announced' => [
            'subject' => 'Hasil Seleksi Telah Diumumkan',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami informasikan bahwa hasil seleksi PPDB RA Fadhilah resmi diumumkan melalui Sistem PPDB RA Fadhilah.\n\nSilakan login ke portal orang tua dan buka menu Status Lulus untuk melihat hasil keputusan seleksi Ananda.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'selection_passed' => [
            'subject' => 'Hasil Seleksi - Lulus',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nPanitia PPDB RA Fadhilah dengan senang hati mengumumkan bahwa Ananda dinyatakan:\n\nLULUS SELEKSI PPDB RA FADHILAH\n\nNomor Pendaftaran: {registration_number}\n\nTanggal Pengumuman: {selection_date}\n\nSilakan melanjutkan ke tahap daftar ulang sesuai dengan jadwal dan ketentuan yang tertera pada Sistem PPDB RA Fadhilah. Selamat kepada Ananda dan keluarga!\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'selection_failed' => [
            'subject' => 'Hasil Seleksi - Tidak Lulus',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nBerdasarkan hasil seleksi akhir PPDB RA Fadhilah, kami menginformasikan bahwa Ananda dinyatakan:\n\nBELUM LULUS SELEKSI\n\nKami mengucapkan terima kasih yang sebesar-besarnya atas kepercayaan Bapak/Ibu yang telah mendaftarkan Ananda di sekolah kami. Semoga Ananda tetap ceria dan selalu semangat dalam melanjutkan jenjang pendidikannya.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        're_registration_instruction' => [
            'subject' => 'Instruksi Daftar Ulang',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nSehubungan dengan kelulusan Ananda pada seleksi PPDB RA Fadhilah, Bapak/Ibu dimohon untuk segera melakukan proses daftar ulang demi mengamankan kuota siswa.\n\nTotal Biaya Daftar Ulang: {re_registration_amount}\n\nSistem Pembayaran: Dapat dibayarkan secara lunas maupun cicilan melalui opsi yang tersedia di sistem.\n\nMetode Pembayaran: Midtrans, transfer BRI, DANA, atau tunai (cash) langsung ke sekolah.\n\nSilakan selesaikan proses ini melalui menu Daftar Ulang pada Sistem PPDB RA Fadhilah.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        're_registration_proof_uploaded' => [
            'subject' => 'Bukti Pembayaran Daftar Ulang Berhasil Diunggah',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami informasikan bahwa bukti pembayaran daftar ulang Ananda telah berhasil diunggah ke sistem.\n\nJumlah Pembayaran: {payment_amount}\n\nMetode Pembayaran: {payment_method}\n\nSaat ini pembayaran sedang dalam proses verifikasi keuangan oleh pihak panitia. Mohon menunggu hingga status dinyatakan valid.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        're_registration_approved' => [
            'subject' => 'Pembayaran Daftar Ulang Terverifikasi Lunas',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami informasikan bahwa seluruh rangkaian pembayaran daftar ulang Ananda telah diverifikasi dan disetujui.\n\nJumlah Pembayaran: {re_registration_amount}\n\nMetode Pembayaran: {payment_method}\n\nStatus Pembayaran: Terverifikasi Lunas\n\nSilakan pantau berkala pengumuman resmi selanjutnya mengenai persiapan sekolah melalui notifikasi email atau WhatsApp ini.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        're_registration_deadline_reminder' => [
            'subject' => 'Reminder Batas Waktu Daftar Ulang',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami mengingatkan kembali bahwa batas akhir waktu daftar ulang Ananda akan ditutup pada:\n\nBatas Waktu: {re_registration_deadline}\n\nMohon Bapak/Ibu segera menyelesaikan pembayaran daftar ulang agar status Ananda dapat diproses sebagai siswa resmi RA Fadhilah.\n\nCatatan: Mohon abaikan pesan ini apabila Bapak/Ibu telah menyelesaikan proses daftar ulang.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        're_registration_installment_reminder' => [
            'subject' => 'Reminder Pembayaran Cicilan Berikutnya',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami mengingatkan bahwa Ananda masih memiliki tagihan cicilan daftar ulang yang perlu diselesaikan dengan rincian berikut:\n\nTagihan: {installment_label}\n\nJumlah Tagihan: {installment_amount}\n\nBatas Pembayaran: {installment_deadline}\n\nPembayaran dapat dilakukan via Midtrans, transfer BRI, DANA, atau tunai ke sekolah.\n\nCatatan: Mohon abaikan pesan ini apabila Bapak/Ibu telah menyelesaikan pembayaran.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        're_registration_final_installment_reminder' => [
            'subject' => 'Reminder Pembayaran Cicilan Terakhir',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nKami mengingatkan bahwa Ananda memiliki tagihan cicilan terakhir daftar ulang yang perlu diselesaikan:\n\nTagihan: {installment_label}\n\nJumlah Tagihan: {installment_amount}\n\nBatas Pembayaran: {installment_deadline}\n\nSilakan melakukan pelunasan melalui metode yang tersedia pada Sistem PPDB RA Fadhilah.\n\nCatatan: Mohon abaikan pesan ini apabila Bapak/Ibu telah melakukan pembayaran.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'student_officially_registered' => [
            'subject' => 'Siswa Resmi Terdaftar',
            'message' => "Yth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nAlhamdulillahirabbil'alamin, kami informasikan bahwa seluruh rangkaian proses pendaftaran dan daftar ulang Ananda telah selesai.\n\nDengan ini, Ananda resmi terdaftar sebagai peserta didik baru RA Fadhilah Tahun Ajaran {academic_year}.\n\nSelamat bergabung menjadi bagian dari keluarga besar RA Fadhilah. Informasi mengenai kegiatan awal masuk sekolah akan disampaikan melalui notifikasi berikutnya.\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB RA Fadhilah",
        ],
        'early_school_activity_info' => [
            'subject' => 'Informasi Kegiatan Awal Sekolah',
            'message' => "PENGUMUMAN PENTING: Informasi Awal Tahun Ajaran Baru RA Fadhilah\n\nYth. Bapak/Ibu Orang Tua/Wali dari Ananda {student_name},\n\nDengan hormat,\nSelamat bergabung di keluarga besar RA Fadhilah! Kami sangat senang menyambut kehadiran Ananda sebagai peserta didik baru. Berikut kami sampaikan informasi awal terkait pengambilan seragam dan jadwal masuk sekolah:\n\n1. Jadwal Pengambilan Seragam\n\nHari/Tanggal: Selasa, 30 Juni 2026\n\nWaktu: 08.00 - 13.00 WIB\n\nTempat: Ruang Tata Usaha RA Fadhilah\n\n2. Hari Pertama Masuk Sekolah\n\nHari/Tanggal: Senin, 06 Juli 2026\n\nWaktu: 07.30 - 11.00 WIB\n\nKeterangan: Kegiatan belajar awal tahun ajaran baru (Masa Orientasi)\n\n3. Perlengkapan yang Perlu Dibawa Anak (Di dalam Tas)\nBapak/Ibu dimohon menyiapkan beberapa kebutuhan berikut demi kenyamanan anak:\n\nPakaian Ganti Lengkap: 1 set baju, celana, pakaian dalam, dan kaus kaki (dimasukkan dalam kantong plastik) untuk antisipasi jika mengompol atau ketumpahan makanan.\n\nKonsumsi: Botol minum dan kotak bekal berisi makanan ringan atau buah-buahan.\n\nSanitasi: Tisu basah dan tisu kering.\n\nKantong Plastik Kosong: 1-2 lembar untuk tempat pakaian kotor.\n\nBuku Penghubung: Apabila sudah diberikan oleh pihak sekolah.\n\nTips: Mohon berikan label nama anak pada tas, botol minum, kotak bekal, dan perlengkapan lainnya agar tidak tertukar. Jika Ananda memiliki riwayat alergi makanan atau kondisi kesehatan khusus, mohon segera mengonfirmasikannya kepada wali kelas.\n\nDemikian informasi ini kami sampaikan. Sampai jumpa di RA Fadhilah!\n\nTerima kasih.\n\nHormat kami,\nPanitia PPDB dan Dewan Guru RA Fadhilah\n\nLayanan Informasi WhatsApp:\n0821-6207-736\n+62 822-8681-7315",
        ],
    ],
];
