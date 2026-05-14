<?php

namespace App\Services;

use App\Models\SchoolContent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ChatbotKnowledgeService
{
    private const MANUAL_KNOWLEDGE = [
        [
            'question' => 'Apakah pendaftaran sudah dibuka?',
            'answer' => 'Ya, pendaftaran PPDB saat ini sudah dibuka. Silakan melakukan pendaftaran melalui website resmi sekolah.',
            'keywords' => ['pendaftaran sudah dibuka', 'ppdb dibuka', 'apakah pendaftaran sudah dibuka'],
        ],
        [
            'question' => 'Sampai kapan pendaftaran dibuka?',
            'answer' => 'Pendaftaran dibuka sampai tanggal 1 Juli 2026 atau sampai kuota terpenuhi.',
            'keywords' => ['sampai kapan pendaftaran', 'batas pendaftaran', 'penutupan ppdb'],
        ],
        [
            'question' => 'Bagaimana cara mendaftar?',
            'answer' => 'Pendaftaran dapat dilakukan secara online melalui website dengan mengisi formulir pendaftaran dan mengunggah berkas yang diperlukan.',
            'keywords' => ['cara mendaftar', 'bagaimana mendaftar', 'daftar online', 'pendaftaran online'],
        ],
        [
            'question' => 'Berapa biaya pendaftarannya?',
            'answer' => 'Pendaftaran dikenakan biaya Rp100.000 per formulir.',
            'keywords' => ['biaya pendaftaran', 'biaya formulir', 'harga formulir'],
        ],
        [
            'question' => 'Berapa biaya masuk sekolah?',
            'answer' => 'Biaya masuk sebesar Rp1.200.000 dan dibayarkan setelah siswa dinyatakan diterima.',
            'keywords' => ['biaya masuk', 'uang masuk', 'biaya sekolah'],
        ],
        [
            'question' => 'Apakah biaya bisa dicicil?',
            'answer' => 'Untuk informasi pembayaran cicilan, silakan menghubungi pihak sekolah atau datang langsung ke bagian administrasi.',
            'keywords' => ['bisa dicicil', 'cicilan', 'angsuran pembayaran'],
        ],
        [
            'question' => 'Apa saja syarat pendaftaran?',
            'answer' => "Persyaratan pendaftaran meliputi:\n- Fotokopi akta kelahiran\n- Fotokopi kartu keluarga\n- Pas foto anak\n- Formulir pendaftaran yang telah diisi",
            'keywords' => ['syarat pendaftaran', 'persyaratan ppdb', 'dokumen pendaftaran', 'persyaratan ppdb'],
        ],
        [
            'question' => 'Apakah harus upload dokumen?',
            'answer' => 'Ya, dokumen dapat diunggah melalui website saat proses pendaftaran berlangsung.',
            'keywords' => ['upload dokumen', 'unggah dokumen', 'harus upload berkas'],
        ],
        [
            'question' => 'Minimal umur berapa untuk mendaftar?',
            'answer' => 'Usia minimal calon siswa adalah 4 tahun untuk kelas PAUD dan 5 tahun untuk kelas TK pada saat tahun ajaran baru dimulai.',
            'keywords' => ['minimal umur', 'usia minimal', 'umur pendaftaran'],
        ],
        [
            'question' => 'Apakah ada tes masuk?',
            'answer' => 'Ya, terdapat wawancara atau tes sederhana untuk calon siswa dan orang tua.',
            'keywords' => ['tes masuk', 'seleksi masuk', 'wawancara masuk'],
        ],
        [
            'question' => 'Kapan jadwal wawancara?',
            'answer' => 'Jadwal wawancara dapat dipilih saat pendaftaran dan akan diinformasikan kembali melalui sistem.',
            'keywords' => ['jadwal wawancara', 'kapan wawancara'],
        ],
        [
            'question' => 'Kapan pengumuman hasil seleksi?',
            'answer' => 'Pengumuman hasil seleksi akan disampaikan melalui website dan dapat dilihat pada akun pendaftaran masing-masing.',
            'keywords' => ['pengumuman hasil seleksi', 'hasil seleksi', 'pengumuman ppdb'],
        ],
        [
            'question' => 'Sekolah ini ada dimana?',
            'answer' => 'Sekolah berlokasi di Jl. Muhajirin, Sidomulyo Bar., Kec. Tampan, Kota Pekanbaru, Riau 28294.',
            'keywords' => ['alamat sekolah', 'lokasi sekolah', 'sekolah dimana', 'sekolah ini ada dimana'],
        ],
        [
            'question' => 'Apa saja fasilitas di sekolah?',
            'answer' => "Fasilitas meliputi:\n- Ruang kelas nyaman\n- Area bermain anak\n- Mushola\n- Perpustakaan\n- dan fasilitas pendukung lainnya",
            'keywords' => ['fasilitas sekolah', 'apa saja fasilitas', 'sarana sekolah'],
        ],
    ];

    public function findDirectAnswer(string $message): ?string
    {
        $normalizedMessage = $this->normalize($message);

        foreach (self::MANUAL_KNOWLEDGE as $item) {
            if ($this->normalize($item['question']) === $normalizedMessage) {
                return $item['answer'];
            }

            foreach ($item['keywords'] as $keyword) {
                if (Str::contains($normalizedMessage, $this->normalize($keyword))) {
                    return $item['answer'];
                }
            }
        }

        return null;
    }

    public function buildPromptContext(): string
    {
        $sections = [];

        $manualKnowledge = $this->formatManualKnowledge();
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

    private function formatManualKnowledge(): string
    {
        return collect(self::MANUAL_KNOWLEDGE)
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
            ->limit(12)
            ->get(['type', 'title', 'excerpt', 'content']);

        if ($items->isEmpty()) {
            return '';
        }

        return $items
            ->map(function (SchoolContent $item) {
                $body = trim((string) ($item->excerpt ?: $item->content ?: ''));
                $body = Str::of(strip_tags($body))
                    ->squish()
                    ->limit(350, '...')
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

        return Str::of($value)->squish()->toString();
    }
}
