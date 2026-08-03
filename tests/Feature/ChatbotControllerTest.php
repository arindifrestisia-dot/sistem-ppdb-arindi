<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mockery;
use Psr\Log\LoggerInterface;
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

    public function test_it_logs_chatbot_question_answer_and_response_time(): void
    {
        $faq = self::SCHOOL_FAQ[0];
        $logger = Mockery::mock(LoggerInterface::class);

        Http::fake();
        Log::shouldReceive('channel')
            ->once()
            ->with('chatbot')
            ->andReturn($logger);

        $logger->shouldReceive('info')
            ->once()
            ->with('Chatbot message answered', Mockery::on(function (array $context) use ($faq) {
                return $context['question'] === $faq['question']
                    && $context['answer'] === $faq['answer']
                    && $context['error_message'] === null
                    && $context['source'] === 'knowledge'
                    && $context['status_code'] === 200
                    && is_float($context['duration_seconds'])
                    && $context['duration_seconds'] >= 0;
            }));

        $this->postJson('/chatbot/message', [
            'message' => $faq['question'],
        ])->assertOk();

        Http::assertNothingSent();
    }

    public function test_it_forwards_non_faq_messages_to_ollama(): void
    {
        Config::set('services.ollama.base_url', 'http://127.0.0.1:11434');
        Config::set('services.ollama.model', 'mistral');
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
                && $request['model'] === 'mistral'
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

    public function test_it_returns_clean_direct_answers_for_school_operational_questions(): void
    {
        Http::fake();

        $cases = [
            [
                'message' => 'Bagaimana cara menghubungi pihak sekolah jika ada pertanyaan?',
                'reply' => 'Anda dapat menghubungi pihak sekolah melalui kontak yang tersedia di website resmi sekolah atau datang langsung ke bagian administrasi sekolah.',
            ],
            [
                'message' => 'Apakah sekolah menyediakan makan siang untuk siswa?',
                'reply' => 'Sekolah tidak menyediakan makan siang, sarapan, atau makan malam. Saat ini yang tersedia adalah MBG (Makanan Bergizi Gratis) dari pemerintah sekitar pukul 10.00 pagi.',
            ],
            [
                'message' => 'Apakah ada diskon untuk anak kedua jika mendaftar selanjutnya?',
                'reply' => 'Informasi diskon untuk anak kedua belum tersedia. Silakan menghubungi pihak sekolah atau datang langsung ke bagian administrasi untuk konfirmasi lebih lanjut.',
            ],
            [
                'message' => 'Jam berapa anak pulang sekolah?',
                'reply' => 'Anak-anak pulang sekolah pukul 12.00, kecuali hari Jumat pulang pukul 11.00 pagi.',
            ],
            [
                'message' => 'Apakah ada kegiatan anak berenang di kolam renang?',
                'reply' => 'Ada. RA Fadhilah memiliki kegiatan outing, termasuk kegiatan berenang di kolam renang yang dilakukan 1-2 kali dalam seminggu.',
            ],
        ];

        foreach ($cases as $case) {
            $this->postJson('/chatbot/message', [
                'message' => $case['message'],
            ])
                ->assertOk()
                ->assertJson([
                    'reply' => $case['reply'],
                    'source' => 'knowledge',
                ])
                ->assertJsonMissing([
                    'reply' => 'Jawaban: '.$case['reply'],
                ]);
        }

        Http::assertNothingSent();
    }

    public function test_it_understands_typo_in_school_questions(): void
    {
        Http::fake();

        $cases = [
            [
                'message' => 'brpa biya formulr?',
                'reply' => 'Pendaftaran dikenakan biaya Rp150.000 per formulir.',
            ],
            [
                'message' => 'jam brpa ank plang skolah?',
                'reply' => 'Anak-anak pulang sekolah pukul 12.00, kecuali hari Jumat pulang pukul 11.00 pagi.',
            ],
            [
                'message' => 'apkah ada kgiatan renang di kolam?',
                'reply' => 'Ada. RA Fadhilah memiliki kegiatan outing, termasuk kegiatan berenang di kolam renang yang dilakukan 1-2 kali dalam seminggu.',
            ],
        ];

        foreach ($cases as $case) {
            $this->postJson('/chatbot/message', [
                'message' => $case['message'],
            ])
                ->assertOk()
                ->assertJson([
                    'reply' => $case['reply'],
                    'source' => 'knowledge',
                ]);
        }

        Http::assertNothingSent();
    }

    public function test_it_strips_internal_answer_labels_from_ollama_reply(): void
    {
        Config::set('services.ollama.base_url', 'http://127.0.0.1:11434');
        Config::set('services.ollama.model', 'mistral');

        Http::fake([
            'http://127.0.0.1:11434/api/generate' => Http::response([
                'response' => 'Pertanyaan: Jam berapa anak pulang sekolah? Jawaban: Anak-anak pulang sekolah pukul 12.00.',
            ], 200),
        ]);

        $this->postJson('/chatbot/message', [
            'message' => 'Apakah boleh menemui langsung kepala sekolah?',
        ])
            ->assertOk()
            ->assertJson([
                'reply' => 'Anak-anak pulang sekolah pukul 12.00.',
                'source' => 'ollama',
            ]);
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
