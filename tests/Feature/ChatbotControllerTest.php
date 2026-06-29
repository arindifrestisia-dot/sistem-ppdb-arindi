<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use ReflectionClass;
use Tests\TestCase;

class ChatbotControllerTest extends TestCase
{
    private const SCHOOL_FAQ = [
        [
            'question' => 'Apakah pendaftaran sudah dibuka?',
            'answer' => 'Ya, pendaftaran PPDB saat ini sudah dibuka. Silakan melakukan pendaftaran melalui website resmi sekolah.',
        ],
        [
            'question' => 'Sampai kapan pendaftaran dibuka?',
            'answer' => 'Pendaftaran dibuka sampai tanggal 1 Juli 2026 atau sampai kuota terpenuhi.',
        ],
        [
            'question' => 'Bagaimana cara mendaftar?',
            'answer' => 'Pendaftaran dapat dilakukan secara online melalui website dengan mengisi formulir pendaftaran dan mengunggah berkas yang diperlukan.',
        ],
        [
            'question' => 'Berapa biaya pendaftarannya?',
            'answer' => 'Pendaftaran dikenakan biaya Rp150.000 per formulir.',
        ],
        [
            'question' => 'Berapa biaya masuk sekolah?',
            'answer' => 'Biaya masuk sebesar Rp1.200.000 dan dibayarkan setelah siswa dinyatakan diterima.',
        ],
        [
            'question' => 'Apakah biaya bisa dicicil?',
            'answer' => 'Untuk informasi pembayaran cicilan, silakan menghubungi pihak sekolah atau datang langsung ke bagian administrasi.',
        ],
        [
            'question' => 'Apa saja syarat pendaftaran?',
            'answer' => "Persyaratan pendaftaran meliputi:\nFotokopi akta kelahiran\nFotokopi kartu keluarga\nPas foto anak\nFormulir pendaftaran yang telah diisi",
        ],
        [
            'question' => 'Apakah harus upload dokumen?',
            'answer' => 'Ya, dokumen dapat diunggah melalui website saat proses pendaftaran berlangsung.',
        ],
        [
            'question' => 'Minimal umur berapa untuk mendaftar?',
            'answer' => 'Usia minimal calon siswa adalah 4 tahun untuk kelas PAUD dan 5 tahun untuk kelas TK pada saat tahun ajaran baru dimulai.',
        ],
        [
            'question' => 'Apakah ada tes masuk?',
            'answer' => 'Ya, terdapat wawancara atau tes sederhana untuk calon siswa dan orang tua.',
        ],
        [
            'question' => 'Kapan jadwal wawancara?',
            'answer' => 'Jadwal wawancara dapat dipilih saat pendaftaran dan akan diinformasikan kembali melalui sistem.',
        ],
        [
            'question' => 'Kapan pengumuman hasil seleksi?',
            'answer' => 'Pengumuman hasil seleksi akan disampaikan melalui website dan dapat dilihat pada akun pendaftaran masing-masing.',
        ],
        [
            'question' => 'Sekolah ini ada dimana?',
            'answer' => 'Sekolah berlokasi di Jl. Muhajirin, Sidomulyo Bar., Kec. Tampan, Kota Pekanbaru, Riau 28294',
        ],
        [
            'question' => 'Apa saja fasilitas di sekolah?',
            'answer' => "Fasilitas meliputi:\nRuang kelas nyaman\nArea bermain anak\nMushola\nPerpustakaan\ndan fasilitas pendukung lainnya",
        ],
    ];

    public function test_it_returns_direct_answer_for_known_faq(): void
    {
        $faq = self::SCHOOL_FAQ[0];

        Http::fake();

        $response = $this->postJson('/chatbot/message', [
            'message' => $faq['question'],
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'reply' => $faq['answer'],
                'source' => 'knowledge',
            ]);

        Http::assertNothingSent();
    }

    public function test_it_forwards_non_faq_messages_to_ollama(): void
    {
        Config::set('services.ollama.base_url', 'http://127.0.0.1:11434');
        Config::set('services.ollama.model', 'gemma:2b');
        Config::set('services.ollama.system_prompt', 'Jawab dalam bahasa Indonesia.');
        Config::set('services.ollama.timeout', 120);
        Config::set('services.ollama.keep_alive', '10m');
        Config::set('services.ollama.num_predict', 128);

        Http::fake([
            'http://127.0.0.1:11434/api/generate' => Http::response([
                'response' => 'Jawaban dari Ollama',
            ], 200),
        ]);

        $response = $this->postJson('/chatbot/message', [
            'message' => 'Apakah boleh menemui langsung kepala sekolah?',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'reply' => 'Jawaban dari Ollama',
                'source' => 'ollama',
            ]);

        Http::assertSent(function ($request) {
            return $request->url() === 'http://127.0.0.1:11434/api/generate'
                && $request['model'] === 'gemma:2b'
                && $request['prompt'] === 'Apakah boleh menemui langsung kepala sekolah?'
                && $request['stream'] === false
                && str_contains($request['system'], 'Jawab dalam bahasa Indonesia.')
                && str_contains($request['system'], 'Kamu boleh menjawab pertanyaan baru')
                && str_contains($request['system'], 'Pengetahuan sekolah yang sudah ditetapkan:')
                && ! str_contains($request['system'], 'Kata kunci:')
                && $request['keep_alive'] === '10m'
                && $request['options']['num_predict'] === 128;
        });
    }

    public function test_it_rejects_out_of_scope_messages_without_calling_ollama(): void
    {
        Http::fake();

        $response = $this->postJson('/chatbot/message', [
            'message' => 'Apa resep kue talam durian?',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'reply' => 'Maaf, silakan ajukan pertanyaan mengenai RA Fadhilah atau penerimaan peserta didik baru di RA Fadhilah.',
                'source' => 'scope',
            ]);

        Http::assertNothingSent();
    }

    public function test_ollama_timeout_is_not_reduced_by_php_execution_limit(): void
    {
        Config::set('services.ollama.timeout', 120);

        $controller = app(\App\Http\Controllers\ChatbotController::class);
        $method = (new ReflectionClass($controller))->getMethod('resolveOllamaTimeout');

        $this->assertSame(120, $method->invoke($controller));
    }

    public function test_school_faq_fixture_contains_the_expected_pairs(): void
    {
        $this->assertCount(14, self::SCHOOL_FAQ);
        $this->assertSame(
            'Pendaftaran dikenakan biaya Rp150.000 per formulir.',
            collect(self::SCHOOL_FAQ)->firstWhere('question', 'Berapa biaya pendaftarannya?')['answer']
        );
        $this->assertSame(
            'Sekolah berlokasi di Jl. Muhajirin, Sidomulyo Bar., Kec. Tampan, Kota Pekanbaru, Riau 28294',
            collect(self::SCHOOL_FAQ)->firstWhere('question', 'Sekolah ini ada dimana?')['answer']
        );
    }
}
