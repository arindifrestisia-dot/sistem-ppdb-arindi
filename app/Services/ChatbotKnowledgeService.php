<?php

namespace App\Services;

use App\Models\SchoolContent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class ChatbotKnowledgeService
{
    public function __construct(
        private readonly ChatbotEmbeddingService $embeddingService,
    ) {}

    private const MANUAL_KNOWLEDGE = [
        [
            'question' => 'Halo',
            'answer' => 'Halo, saya asisten virtual RA Fadhilah. Silakan tanyakan informasi seputar PPDB, syarat pendaftaran, biaya, jadwal, atau alamat sekolah.',
            'keywords' => ['halo',
                'hai',
                'assalamualaikum',
                'selamat pagi',
                'selamat siang',
                'selamat sore'],
        ],
        [
            'question' => 'Apakah pendaftaran sudah dibuka?',
            'answer' => 'Ya, pendaftaran PPDB saat ini sudah dibuka. Silakan melakukan pendaftaran melalui website resmi sekolah.',
            'keywords' => ['pendaftaran sudah dibuka',
                'ppdb sudah dibuka',
                'ppdb dibuka',
                'apakah pendaftaran sudah dibuka'],
        ],
        [
            'question' => 'Sampai kapan pendaftaran dibuka?',
            'answer' => 'Pendaftaran dibuka dari tanggal 1 Oktober 2025 sampai tanggal 1 Juli 2026 atau sampai kuota terpenuhi.',
            'keywords' => ['sampai kapan pendaftaran dibuka',
                'kapan pendaftaran ditutup',
                'batas pendaftaran',
                'jadwal pendaftaran',
                'periode pendaftaran',
                'masa pendaftaran',
                'pendaftaran sampai kapan',
                'ppdb sampai kapan',
                'ppdb ditutup kapan',
                'kapan terakhir daftar',
                'tanggal penutupan pendaftaran',
                'akhir pendaftaran',
                'waktu pendaftaran',
                'jadwal ppdb',
                'tanggal pendaftaran',
                'masih bisa daftar',
                'apakah pendaftaran masih dibuka',
                'apakah masih menerima siswa baru',
                'pendaftaran siswa baru',
                'kuota pendaftaran',
                'kapan penerimaan siswa baru ditutup',
                'batas akhir ppdb',
                'daftar sekolah sampai kapan',
                'deadline pendaftaran',
                'deadline ppdb'],
        ],
        [
            'question' => 'Bagaimana cara mendaftar?',
            'answer' => 'Pendaftaran dapat dilakukan secara online melalui website dengan mengisi formulir pendaftaran dan mengunggah berkas yang diperlukan.',
            'keywords' => ['cara mendaftar',
                'bagaimana mendaftar',
                'daftar online',
                'pendaftaran online'],
        ],
        [
            'question' => 'Berapa biaya pendaftarannya?',
            'answer' => 'Pendaftaran dikenakan biaya Rp150.000 per formulir.',
            'keywords' => ['biaya pendaftaran',
                'biaya formulir',
                'harga formulir',
                'berapa biaya formulir'],
        ],
        [
            'question' => 'Apa strategi pembelajaran di RA?',
            'answer' => 'RA Fadhilah menerapkan strategi pembelajaran yang berfokus pada pembentukan karakter, pengembangan potensi anak, serta penanaman nilai-nilai Islam sejak dini. Strategi pembelajaran yang diterapkan meliputi:
                         1. Adab First
                         2. Active Learning
                         3. Deep Learning
                         4. Outing Class
                         5. Integrasi Nilai Islam
                         6. Pembinaan Prestasi
                         7. Kolaborasi Orang Tua',
            'keywords' => ['strategi pembelajaran',
                'metode pembelajaran',
                'cara belajar',
                'sistem pembelajaran',
                'program pembelajaran',
                'konsep pembelajaran',
                'kurikulum',
                'pola belajar',
                'pendekatan pembelajaran',
                'belajar di ra',
                'pembelajaran di ra fadhilah',
                'metode belajar anak',
                'kegiatan belajar',
                'active learning',
                'deep learning',
                'adab first',
                'outing class',
                'integrasi nilai islam',
                'pembinaan prestasi',
                'kolaborasi orang tua',
                'unggulan pembelajaran',
                'keunggulan belajar',
                'proses belajar mengajar'],
        ],
        [
            'question' => 'Berapa biaya masuk sekolah?',
            'answer' => 'Biaya masuk sebesar Rp1.700.000 dan dibayarkan setelah siswa dinyatakan diterima.',
            'keywords' => ['biaya masuk',
                'uang masuk',
                'biaya sekolah',
                'uang sekolah'],
        ],
        [
            'question' => 'Apa saja biaya masuk sekolah RA?',
            'answer' => 'Biaya masuk RA Fadhilah terdiri dari:
                        - Uang Formulir: Rp150.000
                        - Uang Pembangunan: Rp1.000.000
                        - Uang Seragam: Rp355.000
                        - SPP (sudah termasuk bulan Juli): Rp175.000
                        - Asuransi: Rp20.000
                        Total biaya yang harus dibayarkan adalah Rp1.700.000.',
            'keywords' => ['biaya masuk',
                'biaya pendaftaran',
                'biaya sekolah',
                'total biaya',
                'uang masuk',
                'uang pangkal',
                'rincian biaya',
                'biaya ra fadhilah',
                'berapa biaya masuk',
                'berapa biaya pendaftaran',
                'berapa uang masuk',
                'berapa total biaya',
                'berapa biaya sekolah',
                'harga pendaftaran',
                'uang formulir',
                'uang pembangunan',
                'uang seragam',
                'biaya seragam',
                'spp',
                'biaya spp',
                'asuransi',
                'rincian pembayaran',
                'biaya ppdb',
                'pembayaran pendaftaran',
                'daftar sekolah bayar berapa',
                'masuk ra bayar berapa',
                'biaya siswa baru',
                'biaya pendidikan',
                'total pembayaran',
                'berapa biaya ra'],
        ],
        [
            'question' => 'Apakah biaya bisa dicicil?',
            'answer' => 'Ya bisa. Untuk informasi pembayaran cicilan, silakan menghubungi pihak sekolah atau datang langsung ke bagian administrasi.',
            'keywords' => ['bisa dicicil',
                'cicilan',
                'angsuran pembayaran',
                'angsuran'],
        ],
        [
            'question' => 'Bagaimana cara menghubungi pihak sekolah jika ada pertanyaan?',
            'answer' => 'Anda dapat menghubungi pihak sekolah melalui kontak yang tersedia di website resmi sekolah atau datang langsung ke bagian administrasi sekolah.',
            'keywords' => [
                'menghubungi pihak sekolah',
                'hubungi pihak sekolah',
                'cara menghubungi sekolah',
                'kontak sekolah',
                'nomor sekolah',
                'telepon sekolah',
                'email sekolah',
                'bagian administrasi sekolah',
                'ada pertanyaan',
            ],
        ],
        [
            'question' => 'Apakah sekolah menyediakan makan siang untuk siswa?',
            'answer' => 'Sekolah tidak menyediakan makan siang, sarapan, atau makan malam. Saat ini yang tersedia adalah MBG (Makanan Bergizi Gratis) dari pemerintah sekitar pukul 10.00 pagi.',
            'keywords' => [
                'makan siang',
                'sarapan',
                'makan pagi',
                'makan malam',
                'menyediakan makan',
                'makanan siswa',
                'mbg',
                'makanan bergizi gratis',
                'jam makan',
                'katering',
                'catering',
            ],
        ],
        [
            'question' => 'Apakah ada diskon untuk anak kedua jika mendaftar selanjutnya?',
            'answer' => 'Informasi diskon untuk anak kedua belum tersedia. Silakan menghubungi pihak sekolah atau datang langsung ke bagian administrasi untuk konfirmasi lebih lanjut.',
            'keywords' => [
                'diskon anak kedua',
                'potongan anak kedua',
                'diskon saudara',
                'diskon kakak adik',
                'potongan biaya',
                'diskon pendaftaran',
                'anak kedua',
                'mendaftar selanjutnya',
            ],
        ],
        [
            'question' => 'Jam berapa anak pulang sekolah?',
            'answer' => 'Anak-anak pulang sekolah pukul 12.00, kecuali hari Jumat pulang pukul 11.00 pagi.',
            'keywords' => [
                'jam pulang',
                'pulang sekolah',
                'anak pulang',
                'siswa pulang',
                'pukul berapa pulang',
                'jam berapa anak pulang',
                'jadwal pulang',
                'pulang hari jumat',
            ],
        ],
        [
            'question' => 'Apa saja syarat pendaftaran?',
            'answer' => "Persyaratan pendaftaran meliputi:\n- Fotokopi akta kelahiran\n- Fotokopi kartu keluarga\n- Pas foto anak\n- Formulir pendaftaran yang telah diisi",
            'keywords' => ['syarat pendaftaran',
                'syarat ppdb',
                'apa saja syarat ppdb',
                'persyaratan ppdb',
                'dokumen pendaftaran'],
        ],
        [
            'question' => 'Apakah harus upload dokumen?',
            'answer' => 'Ya, dokumen dapat diunggah melalui website saat proses pendaftaran berlangsung.',
            'keywords' => ['upload dokumen',
                'unggah dokumen',
                'harus upload berkas'],
        ],
        [
            'question' => 'Minimal umur berapa untuk mendaftar?',
            'answer' => 'Usia minimal calon siswa adalah 4 tahun untuk kelas PAUD dan 5 tahun untuk kelas TK pada saat tahun ajaran baru dimulai.',
            'keywords' => ['minimal umur mendaftar',
                'usia minimal',
                'umur pendaftaran'],
        ],
        [
            'question' => 'Apakah ada tes masuk?',
            'answer' => 'Ya, terdapat wawancara atau tes sederhana untuk calon siswa dan orang tua.',
            'keywords' => ['tes masuk',
                'seleksi masuk',
                'wawancara masuk'],
        ],
        [
            'question' => 'Kapan jadwal wawancara?',
            'answer' => 'Jadwal wawancara dapat dipilih saat pendaftaran dan akan diinformasikan kembali melalui sistem.',
            'keywords' => ['jadwal wawancara',
                'wawancara',
                'kapan wawancara'],
        ],
        [
            'question' => 'Kapan pengumuman hasil seleksi?',
            'answer' => 'Pengumuman hasil seleksi akan disampaikan melalui website dan dapat dilihat pada akun pendaftaran masing-masing.',
            'keywords' => ['pengumuman hasil seleksi',
                'hasil seleksi',
                'pengumuman ppdb'],
        ],
        [
            'question' => 'Sekolah ini ada dimana?',
            'answer' => 'Sekolah berlokasi di Jl. Muhajirin, Sidomulyo Bar., Kec. Tampan, Kota Pekanbaru, Riau 28294.',
            'keywords' => ['alamat sekolah',
                'lokasi sekolah',
                'sekolah dimana',
                'sekolah ini ada dimana'],
        ],
        [
            'question' => 'Apa saja fasilitas di sekolah?',
            'answer' => "Fasilitas meliputi:\n- Ruang kelas nyaman\n- Area bermain anak\n- Mushola\n- Perpustakaan\n- Toilet\n- CCTV di setiap ruangan\n- Free Wifi\n- dan fasilitas pendukung lainnya",
            'keywords' => ['fasilitas sekolah',
                'apa saja fasilitas',
                'sarana sekolah',
                'fasilitas ra fadhilah',
                'sarana dan prasarana',
                'fasilitas belajar',
                'fasilitas pendidikan',
                'fasilitas yang tersedia',
                'fasilitas untuk anak',
                'kelengkapan sekolah',
                'fasilitas kelas',
                'ruang kelas',
                'area bermain',
                'tempat bermain anak',
                'mushola',
                'perpustakaan',
                'toilet sekolah',
                'cctv sekolah',
                'wifi sekolah',
                'free wifi',
                'fasilitas pendukung',
                'lingkungan sekolah',
                'gedung sekolah',
                'prasarana sekolah',
                'sekolah punya apa saja',
                'apa saja yang ada di sekolah',
                'fasilitas ra',
                'sarana ra',
                'apakah ada perpustakaan',
                'apakah ada area bermain',
                'apakah ada mushola',
                'apakah ada wifi',
                'apakah ada cctv',
                'fasilitas anak belajar',
                'keunggulan fasilitas sekolah'],
        ],
        [
            'question' => 'Apa saja dokumen yang diperlukan?',
            'answer' => "Dokumen yang perlu disiapkan meliputi:\n- Pas foto anak ukuran 3x4 sebanyak 2 lembar\n- Akta lahir anak\n- Scan KTP kedua orang tua\n- Kartu keluarga",
            'keywords' => [
                'dokumen yang diperlukan',
                'dokumen pendaftaran',
                'berkas pendaftaran',
                'persyaratan pendaftaran',
                'syarat pendaftaran',
                'syarat masuk sekolah',
                'berkas yang dibutuhkan',
                'berkas yang diperlukan',
                'dokumen ppdb',
                'persyaratan ppdb',
                'syarat ppdb',
                'apa saja dokumen yang harus disiapkan',
                'apa saja berkas yang harus disiapkan',
                'dokumen untuk daftar sekolah',
                'berkas untuk daftar sekolah',
                'syarat daftar sekolah',
                'persyaratan masuk',
                'dokumen masuk sekolah',
                'scan ktp orang tua',
                'kartu keluarga',
                'akta lahir',
                'pas foto anak',
                'berkas administrasi',
            ],
        ],
        [
            'question' => 'Pas foto ukuran berapa?',
            'answer' => 'Ukuran 3x4 sebanyak 2 lembar',
            'keywords' => [
                'pas foto',
                'ukuran foto',
                'ukuran pas foto yang diperlukan',
                'pas foto harus ukuran berapa',
                'foto anak ukuran berapa',
                'berapa ukuran foto yang harus diupload',
                'berapa ukuran pas foto untuk pendaftaran',
                'pas foto 3x4 atau 4x6',
                'foto yang diminta ukuran berapa',
                'ukuran foto pendaftaran berapa',
                'pas foto anak ukuran berapa',
                'foto untuk syarat pendaftaran ukuran berapa',
                'foto yang harus diserahkan ukuran berapa',
                'berapa lembar pas foto yang dibutuhkan',
                'berapa lembar',
                'berapa lembar pas foto',
                'pas foto berwarna atau hitam putih',
                'foto anak harus seperti apa',
                'apakah perlu pas foto',
                'syarat foto untuk pendaftaran',
                'foto yang diperlukan untuk daftar sekolah',
                'pas foto background warna apa',
                'foto ukuran 3x4 kah',
            ],
        ],
        [
            'question' => 'Apakah ada kegiatan anak berenang di kolam renang?',
            'answer' => 'Ada. RA Fadhilah memiliki kegiatan outing, termasuk kegiatan berenang di kolam renang yang dilakukan 1-2 kali dalam seminggu.',
            'keywords' => [
                'berenang',
                'kolam renang',
                'kegiatan berenang',
                'anak berenang',
                'outing berenang',
                'renang',
                'swimming',
                '1-2 kali seminggu',
                'satu dua kali seminggu',
            ],
        ],
        [
            'question' => 'Apa saja program kegiatan dan pembelajaran di RA Fadhilah?',
            'answer' => "RA Fadhilah memiliki berbagai program kegiatan dan pembelajaran yang dirancang untuk mengembangkan aspek keagamaan, akademik, bahasa, serta keterampilan sosial anak. Program-program tersebut meliputi:
                         - Tahfidz Al-Qur\'an
                         - Pembelajaran Iqro
                         - Hafalan Hadis Pilihan
                         - Praktik Ibadah Sehari-hari
                         - Perkenalan Bahasa Arab
                         - Perkenalan Bahasa Inggris
                         - Calistung (Membaca, Menulis, dan Berhitung)
                         - Outing Class, seperti berenang, kunjungan ke kebun binatang, museum, Pusat Wilayah (Puswil), agrowisata, dan berbagai kegiatan edukatif lainnya.",
            'keywords' => [
                'program kegiatan',
                'program pembelajaran',
                'kegiatan sekolah',
                'kegiatan belajar',
                'program unggulan',
                'program ra fadhilah',
                'kurikulum',
                'apa saja program sekolah',
                'apa saja kegiatan sekolah',
                'kegiatan anak di sekolah',
                'tahfidz',
                'hafalan quran',
                'menghafal quran',
                'iqro',
                'belajar iqro',
                'hafalan hadist',
                'hafalan hadis',
                'praktek ibadah',
                'praktik ibadah',
                'belajar sholat',
                'bahasa arab',
                'perkenalan bahasa arab',
                'bahasa inggris',
                'perkenalan bahasa inggris',
                'english for kids',
                'calistung',
                'belajar membaca',
                'belajar menulis',
                'belajar berhitung',
                'outing class',
                'kegiatan luar kelas',
                'berenang',
                'kebun binatang',
                'museum',
                'agrowisata',
                'kunjungan edukatif',
                'field trip',
                'ekstrakurikuler',
                'kegiatan siswa',
                'aktivitas anak',
                'program pendidikan',
                'pembelajaran islam',
                'program tahfidz',
                'keunggulan program',
                'unggulan sekolah',
            ],
        ],
        [
            'question' => 'Kapan dan di mana pendaftaran peserta didik baru?',
            'answer' => 'Pendaftaran peserta didik baru RA Fadhilah dilayani setiap hari Senin sampai Sabtu pukul 08.00 - 13.00 WIB. Orang tua dapat datang langsung ke RA Fadhilah yang beralamat di Jl. Muhajirin, Kelurahan Sidomulyo Barat, Kecamatan Tuah Madani untuk mendapatkan informasi dan melakukan proses pendaftaran.',
            'keywords' => ['jam pendaftaran',
                'waktu pendaftaran',
                'pendaftaran buka jam berapa',
                'pendaftaran tutup jam berapa',
                'jam operasional pendaftaran',
                'jam pelayanan',
                'jam pelayanan ppdb',
                'hari pendaftaran',
                'pendaftaran hari apa',
                'pendaftaran senin sampai sabtu',
                'kapan bisa daftar',
                'kapan pendaftaran dilayani',
                'alamat pendaftaran',
                'daftar langsung ke sekolah',
                'cara datang ke sekolah',
                'kantor pendaftaran',
                'tempat pendaftaran',
                'jl muhajirin',
                'sidomulyo barat',
                'tuah madani',
                'jam kerja sekolah',
                'jam layanan sekolah',
                'informasi pendaftaran', ],
        ],
    ];

    public function findDirectAnswer(string $message): ?string
    {
        $normalizedMessage = $this->normalize($message);
        $bestFuzzyMatch = null;

        foreach (self::MANUAL_KNOWLEDGE as $item) {
            if ($this->normalize($item['question']) === $normalizedMessage) {
                return $item['answer'];
            }

            foreach ($item['keywords'] as $keyword) {
                $normalizedKeyword = $this->normalize($keyword);

                if (Str::contains($normalizedMessage, $normalizedKeyword)) {
                    return $item['answer'];
                }

                $score = $this->fuzzyPhraseScore($normalizedMessage, $normalizedKeyword);

                if ($score > ($bestFuzzyMatch['score'] ?? 0)) {
                    $bestFuzzyMatch = [
                        'answer' => $item['answer'],
                        'score' => $score,
                    ];
                }
            }

            $score = $this->fuzzyPhraseScore($normalizedMessage, $this->normalize($item['question']));

            if ($score > ($bestFuzzyMatch['score'] ?? 0)) {
                $bestFuzzyMatch = [
                    'answer' => $item['answer'],
                    'score' => $score,
                ];
            }
        }

        return ($bestFuzzyMatch['score'] ?? 0) >= 5
            ? $bestFuzzyMatch['answer']
            : null;
    }

    public function findDataBackedAnswer(string $message): ?string
    {
        $normalizedMessage = $this->normalize($message);

        if (
            Str::contains($normalizedMessage, ['berapa guru', 'jumlah guru', 'berapa tenaga pendidik', 'jumlah tenaga pendidik'])
            && Schema::hasTable('school_contents')
        ) {
            $teachers = SchoolContent::query()
                ->where('type', SchoolContent::TYPE_TEACHER)
                ->published()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->pluck('title')
                ->filter()
                ->values();

            if ($teachers->isNotEmpty()) {
                return 'Saat ini terdapat '.$teachers->count().' tenaga pendidik di RA Fadhilah, antara lain '.$teachers->take(5)->implode(', ').'.';
            }
        }

        return null;
    }

    public function isSchoolScope(string $message): bool
    {
        $normalizedMessage = $this->normalize($message);

        if (preg_match('/\bra\b/u', $normalizedMessage) === 1) {
            return true;
        }

        return Str::contains($normalizedMessage, [
            'ra fadhilah',
            'tk',
            'paud',
            'sekolah',
            'ppdb',
            'daftar',
            'pendaftaran',
            'mendaftar',
            'daftarkan',
            'formulir',
            'siswa',
            'calon siswa',
            'anak',
            'orang tua',
            'masuk',
            'kesini',
            'ke sini',
            'bayar',
            'pembayaran',
            'biaya',
            'cash',
            'tunai',
            'transfer',
            'dana',
            'daftar ulang',
            'lulus',
            'kelulusan',
            'seleksi',
            'wawancara',
            'berkas',
            'dokumen',
            'akta',
            'kartu keluarga',
            'ktp',
            'pas foto',
            'umur',
            'usia',
            'alamat',
            'lokasi',
            'fasilitas',
            'program',
            'kegiatan',
            'belajar',
            'kurikulum',
            'guru',
            'kelas',
            'jadwal',
        ]);
    }

    public function buildScopedFallbackAnswer(string $message): ?string
    {
        if ($answer = $this->findDataBackedAnswer($message)) {
            return $answer;
        }

        if ($answer = $this->buildRetrievedFallbackAnswer($message)) {
            return $answer;
        }

        if (! $this->isSchoolScope($message)) {
            return null;
        }

        return 'Maaf, informasi yang Anda cari belum tersedia. Silakan hubungi pihak sekolah untuk informasi lebih lanjut.';
    }

    private function buildRetrievedFallbackAnswer(string $message): ?string
    {
        try {
            $results = $this->embeddingService->search($message, 1, 0.25);
        } catch (Throwable $exception) {
            report($exception);

            return null;
        }

        if ($results === []) {
            return null;
        }

        $content = $this->stripInternalMetadata((string) $results[0]['chunk']->content);

        if (preg_match('/Jawaban:\s*(.*?)$/s', $content, $matches)) {
            return trim($matches[1]);
        }

        return 'Berdasarkan data sekolah: '.Str::of($content)
            ->replaceMatches('/^(Kategori|Judul|Isi):\s*/m', '')
            ->squish()
            ->limit(420, '.')
            ->toString();
    }

    public function buildPromptContext(?string $message = null): string
    {
        if ($message !== null && trim($message) !== '') {
            $embeddingKnowledge = $this->formatEmbeddingKnowledge($message);

            if ($embeddingKnowledge !== '') {
                return "Konteks sekolah hasil pencarian embedding:\n".$embeddingKnowledge;
            }
        }

        $sections = [];

        $manualKnowledge = $this->formatManualKnowledge($message);
        if ($manualKnowledge !== '') {
            $sections[] = "Pengetahuan sekolah yang sudah ditetapkan:\n".$manualKnowledge;
        }

        $databaseKnowledge = $this->formatDatabaseKnowledge();
        if ($databaseKnowledge !== '') {
            $sections[] = "Data sekolah dari database:\n".$databaseKnowledge;
        }

        return implode("\n\n", $sections);
    }

    public function allManualKnowledge(): array
    {
        return self::MANUAL_KNOWLEDGE;
    }

    public function knowledgeChunks(): array
    {
        return array_merge(
            $this->manualKnowledgeChunks(),
            $this->operationalKnowledgeChunks(),
            $this->databaseKnowledgeChunks()
        );
    }

    private function formatEmbeddingKnowledge(string $message): string
    {
        try {
            $results = $this->embeddingService->search(
                $message,
                (int) config('services.ollama.rag_limit', 5)
            );
        } catch (Throwable $exception) {
            report($exception);

            return '';
        }

        if ($results === []) {
            return '';
        }

        return collect($results)
            ->map(function (array $result) {
                $chunk = $result['chunk'];
                $score = number_format((float) $result['score'], 3);

                return sprintf(
                    '[%s | relevansi %s] %s',
                    $chunk->title ?: $chunk->source_type,
                    $score,
                    $this->stripInternalMetadata((string) $chunk->content)
                );
            })
            ->implode("\n\n");
    }

    private function manualKnowledgeChunks(): array
    {
        return collect(self::MANUAL_KNOWLEDGE)
            ->values()
            ->map(function (array $item, int $index) {
                return [
                    'source_key' => 'manual:'.$index,
                    'source_type' => 'manual',
                    'source_id' => null,
                    'title' => $item['question'],
                    'content' => trim("Pertanyaan: {$item['question']}\nJawaban: {$item['answer']}"),
                ];
            })
            ->all();
    }

    private function operationalKnowledgeChunks(): array
    {
        $formAmount = $this->formatCurrency((int) config('ppdb_notifications.amounts.form', 150000));
        $reRegistrationAmount = $this->formatCurrency((int) config('ppdb_notifications.amounts.re_registration', 1500000));

        return [
            [
                'source_key' => 'operational:form-payment',
                'source_type' => 'operational',
                'source_id' => null,
                'title' => 'Pembayaran formulir PPDB',
                'content' => "Biaya formulir PPDB RA Fadhilah adalah {$formAmount}. Orang tua dapat membayar melalui Midtrans, transfer BRI/DANA, atau cash ke sekolah. Untuk transfer BRI/DANA, bukti pembayaran perlu diunggah. Untuk cash ke sekolah, upload bukti tidak diperlukan.",
            ],
            [
                'source_key' => 'operational:re-registration-payment',
                'source_type' => 'operational',
                'source_id' => null,
                'title' => 'Pembayaran daftar ulang',
                'content' => "Biaya daftar ulang RA Fadhilah adalah {$reRegistrationAmount}. Pembayaran daftar ulang tersedia setelah calon siswa dinyatakan lulus. Orang tua dapat membayar melalui Midtrans, transfer BRI/DANA, atau cash ke sekolah. Untuk transfer BRI/DANA, bukti pembayaran perlu diunggah. Untuk cash ke sekolah, upload bukti tidak diperlukan.",
            ],
            [
                'source_key' => 'operational:registration-flow',
                'source_type' => 'operational',
                'source_id' => null,
                'title' => 'Alur pendaftaran PPDB',
                'content' => 'Alur pendaftaran PPDB RA Fadhilah: orang tua membeli formulir, mengisi data diri calon siswa dan orang tua, mengunggah berkas persyaratan, memilih jadwal wawancara, menunggu verifikasi dan hasil seleksi dari panitia, lalu melakukan daftar ulang jika dinyatakan lulus.',
            ],
        ];
    }

    private function databaseKnowledgeChunks(): array
    {
        if (! Schema::hasTable('school_contents')) {
            return [];
        }

        return SchoolContent::query()
            ->whereIn('type', [
                SchoolContent::TYPE_INFORMATION,
                SchoolContent::TYPE_FACILITY,
                SchoolContent::TYPE_ACTIVITY,
                SchoolContent::TYPE_ACHIEVEMENT,
                SchoolContent::TYPE_TEACHER,
            ])
            ->published()
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->get(['id', 'type', 'title', 'excerpt', 'content'])
            ->flatMap(function (SchoolContent $item) {
                $body = Str::of(strip_tags((string) ($item->excerpt ?: $item->content ?: '')))
                    ->squish()
                    ->toString();

                $text = trim("Kategori: {$item->type}\nJudul: {$item->title}\nIsi: {$body}");

                return collect($this->splitIntoChunks($text))
                    ->values()
                    ->map(function (string $chunk, int $index) use ($item) {
                        return [
                            'source_key' => 'school_content:'.$item->id.':'.$index,
                            'source_type' => 'school_content',
                            'source_id' => $item->id,
                            'title' => $item->title,
                            'content' => $chunk,
                        ];
                    });
            })
            ->values()
            ->all();
    }

    private function splitIntoChunks(string $text, int $maxLength = 900): array
    {
        $text = Str::of($text)->squish()->toString();

        if ($text === '') {
            return [];
        }

        if (strlen($text) <= $maxLength) {
            return [$text];
        }

        $chunks = [];
        $current = '';

        foreach (preg_split('/(?<=[.!?])\s+/', $text) ?: [$text] as $sentence) {
            if (strlen($current.' '.$sentence) > $maxLength && $current !== '') {
                $chunks[] = trim($current);
                $current = '';
            }

            $current = trim($current.' '.$sentence);
        }

        if ($current !== '') {
            $chunks[] = $current;
        }

        return $chunks;
    }

    private function formatCurrency(int $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    private function stripInternalMetadata(string $content): string
    {
        return Str::of($content)
            ->replaceMatches('/\s*Kata kunci:\s*.*$/su', '')
            ->squish()
            ->toString();
    }

    private function formatManualKnowledge(?string $message = null): string
    {
        $items = collect(self::MANUAL_KNOWLEDGE);

        if ($message !== null && trim($message) !== '') {
            $normalizedMessage = $this->normalize($message);
            $messageWords = collect(explode(' ', $normalizedMessage))
                ->filter(fn (string $word) => strlen($word) >= 4)
                ->values();
            $messageTerms = $messageWords
                ->concat($messageWords->flatMap(function (string $word) {
                    return match (true) {
                        Str::contains($word, 'daftar') => ['daftar', 'mendaftar', 'pendaftaran'],
                        Str::contains($word, 'bayar') => ['bayar', 'pembayaran', 'biaya'],
                        default => [],
                    };
                }))
                ->unique()
                ->values();

            $items = $items
                ->map(function (array $item) use ($normalizedMessage, $messageTerms) {
                    $keywordMatches = collect($item['keywords'])
                        ->filter(fn (string $keyword) => Str::contains($normalizedMessage, $this->normalize($keyword)))
                        ->count();

                    $haystack = $this->normalize($item['question'].' '.implode(' ', $item['keywords']));
                    $wordMatches = $messageTerms
                        ->filter(fn (string $word) => Str::contains($haystack, $word))
                        ->count();

                    return [
                        'item' => $item,
                        'score' => ($keywordMatches * 3) + $wordMatches,
                    ];
                })
                ->filter(fn (array $scoredItem) => $scoredItem['score'] > 0)
                ->sortByDesc('score')
                ->take(6)
                ->pluck('item');
        }

        if ($items->isEmpty()) {
            return '';
        }

        return $items
            ->map(fn (array $item) => 'Q: '.$item['question']."\nA: ".$item['answer'])
            ->implode("\n\n");
    }

    private function formatDatabaseKnowledge(): string
    {
        if (! Schema::hasTable('school_contents')) {
            return '';
        }

        $items = SchoolContent::query()
            ->whereIn('type', [
                SchoolContent::TYPE_INFORMATION,
                SchoolContent::TYPE_FACILITY,
                SchoolContent::TYPE_ACTIVITY,
                SchoolContent::TYPE_ACHIEVEMENT,
            ])
            ->published()
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->limit(6)
            ->get(['type', 'title', 'excerpt', 'content']);

        if ($items->isEmpty()) {
            return '';
        }

        return $items
            ->map(function (SchoolContent $item) {
                $body = trim((string) ($item->excerpt ?: $item->content ?: ''));
                $body = Str::of(strip_tags($body))
                    ->squish()
                    ->limit(220, '...')
                    ->toString();

                return sprintf(
                    '[%s] %s%s',
                    $item->type,
                    $item->title,
                    $body !== '' ? ': '.$body : ''
                );
            })
            ->implode("\n");
    }

    private function normalize(string $value): string
    {
        $value = Str::lower(trim($value));
        $value = preg_replace('/[^\pL\pN\s]/u', '', $value) ?? $value;
        $value = preg_replace('/\bdi\s+(tutup|buka)\b/u', 'di$1', $value) ?? $value;

        return Str::of($value)->squish()->toString();
    }

    private function fuzzyPhraseScore(string $normalizedMessage, string $normalizedPhrase): int
    {
        if ($normalizedMessage === '' || $normalizedPhrase === '') {
            return 0;
        }

        if (Str::contains($normalizedMessage, $normalizedPhrase)) {
            return 20;
        }

        $messageWords = $this->significantWords($normalizedMessage);
        $phraseWords = $this->significantWords($normalizedPhrase);

        if ($messageWords === [] || $phraseWords === []) {
            return 0;
        }

        $matchedWords = 0;

        foreach ($phraseWords as $phraseWord) {
            foreach ($messageWords as $messageWord) {
                if ($this->wordsAreSimilar($messageWord, $phraseWord)) {
                    $matchedWords++;

                    break;
                }
            }
        }

        $requiredMatches = count($phraseWords) <= 3
            ? count($phraseWords)
            : (int) ceil(count($phraseWords) * 0.75);

        if ($matchedWords < $requiredMatches) {
            return 0;
        }

        similar_text($normalizedMessage, $normalizedPhrase, $similarity);

        return ($matchedWords * 3) + (int) floor($similarity / 20);
    }

    /**
     * @return array<int, string>
     */
    private function significantWords(string $normalizedText): array
    {
        $stopWords = [
            'apa', 'apakah', 'ada', 'yang', 'untuk', 'dari', 'dan', 'atau', 'jika', 'kalau',
            'ke', 'di', 'ini', 'itu', 'saya', 'kami', 'mau', 'ingin', 'tolong',
        ];

        return collect(explode(' ', $normalizedText))
            ->filter(fn (string $word) => strlen($word) >= 3 && ! in_array($word, $stopWords, true))
            ->values()
            ->all();
    }

    private function wordsAreSimilar(string $messageWord, string $phraseWord): bool
    {
        if ($messageWord === $phraseWord) {
            return true;
        }

        if (strlen($messageWord) < 4 || strlen($phraseWord) < 4) {
            return false;
        }

        if (Str::startsWith($messageWord, substr($phraseWord, 0, 4)) || Str::startsWith($phraseWord, substr($messageWord, 0, 4))) {
            return true;
        }

        $distance = levenshtein($messageWord, $phraseWord);
        $maxLength = max(strlen($messageWord), strlen($phraseWord));
        $allowedDistance = $maxLength <= 5 ? 1 : (int) floor($maxLength * 0.28);

        return $distance <= max(1, $allowedDistance);
    }
}
