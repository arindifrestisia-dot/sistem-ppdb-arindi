<?php

namespace App\Http\Controllers;

use App\Services\ChatbotKnowledgeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class ChatbotController extends Controller
{
    public function __construct(
        private readonly ChatbotKnowledgeService $knowledgeService
    ) {
    }

    public function message(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        if ($directAnswer = $this->knowledgeService->findDirectAnswer($validated['message'])) {
            return response()->json([
                'reply' => $directAnswer,
                'source' => 'knowledge',
            ]);
        }

        if ($dataAnswer = $this->knowledgeService->findDataBackedAnswer($validated['message'])) {
            return response()->json([
                'reply' => $dataAnswer,
                'source' => 'database',
            ]);
        }

        if (! $this->knowledgeService->isSchoolScope($validated['message'])) {
            return response()->json([
                'reply' => $this->outOfScopeReply(),
                'source' => 'scope',
            ]);
        }

        $baseUrl = rtrim((string) config('services.ollama.base_url'), '/');
        $model = (string) config('services.ollama.model');
        $systemPrompt = (string) config('services.ollama.system_prompt');
        $timeout = $this->resolveOllamaTimeout();
        $keepAlive = (string) config('services.ollama.keep_alive', '10m');
        $numPredict = (int) config('services.ollama.num_predict', 256);
        $numCtx = (int) config('services.ollama.num_ctx', 1024);

        if ($baseUrl === '' || $model === '') {
            if ($fallbackAnswer = $this->knowledgeService->buildScopedFallbackAnswer($validated['message'])) {
                return response()->json([
                    'reply' => $fallbackAnswer,
                    'source' => 'fallback',
                ]);
            }

            return response()->json([
                'message' => 'Konfigurasi chatbot belum lengkap. Isi OLLAMA_BASE_URL dan OLLAMA_MODEL terlebih dahulu.',
            ], 500);
        }

        $this->extendPhpExecutionTime($timeout);

        $payload = [
            'model' => $model,
            'prompt' => $validated['message'],
            'stream' => false,
            'keep_alive' => $keepAlive,
            'options' => [
                'num_predict' => $numPredict,
                'num_ctx' => $numCtx,
            ],
        ];

        if ($systemPrompt !== '') {
            $payload['system'] = $this->buildSystemPrompt($systemPrompt, $validated['message']);
        }

        try {
            $response = Http::acceptJson()
                ->timeout($timeout)
                ->connectTimeout(10)
                ->post(
                    "{$baseUrl}/api/generate",
                    $payload
                );
        } catch (Throwable $exception) {
            report($exception);

            if ($fallbackAnswer = $this->knowledgeService->buildScopedFallbackAnswer($validated['message'])) {
                return response()->json([
                    'reply' => $fallbackAnswer,
                    'source' => 'fallback',
                ]);
            }

            return response()->json([
                'message' => 'Koneksi ke Ollama gagal atau waktunya habis. Coba lagi, ringkas pertanyaan, atau gunakan model yang lebih ringan.',
            ], 504);
        }

        if ($response->failed()) {
            report('Ollama request failed: '.$response->body());

            if ($fallbackAnswer = $this->knowledgeService->buildScopedFallbackAnswer($validated['message'])) {
                return response()->json([
                    'reply' => $fallbackAnswer,
                    'source' => 'fallback',
                ]);
            }

            return response()->json([
                'message' => 'Chatbot sedang tidak bisa menjawab. Silakan coba beberapa saat lagi.',
            ], 502);
        }

        $data = $response->json();
        $reply = data_get($data, 'response');

        if (! is_string($reply) || trim($reply) === '') {
            if ($fallbackAnswer = $this->knowledgeService->buildScopedFallbackAnswer($validated['message'])) {
                return response()->json([
                    'reply' => $fallbackAnswer,
                    'source' => 'fallback',
                ]);
            }

            return response()->json([
                'message' => 'Ollama merespons, tetapi format jawabannya belum dikenali.',
                'raw' => $data,
            ], 502);
        }

        return response()->json([
            'reply' => $reply,
            'source' => 'ollama',
        ]);
    }

    private function findFaqAnswer(string $message): ?string
    {
        $normalizedMessage = $this->normalizeText($message);

        foreach (self::FAQ as $item) {
            if ($this->normalizeText($item['question']) === $normalizedMessage) {
                return $item['answer'];
            }
        }

        return null;
    }

    private function resolveOllamaTimeout(): int
    {
        return max(10, (int) config('services.ollama.timeout', 45));
    }

    private function extendPhpExecutionTime(int $ollamaTimeout): void
    {
        if (! function_exists('set_time_limit')) {
            return;
        }

        $embeddingTimeout = max(0, (int) config('services.ollama.embedding_timeout', 60));
        $requestBudget = $ollamaTimeout + $embeddingTimeout + 30;

        @set_time_limit($requestBudget);
    }

    private function buildSystemPrompt(string $basePrompt, string $message): string
    {
        $prompt = trim($basePrompt)."\n\n"
            .'Aturan tambahan:'."\n"
            ."- Kamu boleh menjawab pertanyaan baru yang tidak ada persis di knowledge selama masih berkaitan dengan RA Fadhilah, sekolah, PPDB, pendaftaran, pembayaran, seleksi, daftar ulang, fasilitas, program, atau kegiatan sekolah.\n"
            ."- Gunakan pengetahuan sekolah yang diberikan dan data database sebagai sumber utama.\n"
            ."- Jika pertanyaannya masih seputar sekolah/PPDB tetapi detailnya tidak ada di data, berikan jawaban umum yang aman dan arahkan untuk konfirmasi ke panitia/sekolah.\n"
            ."- Jangan mengarang angka, tanggal, nominal, alamat, atau kebijakan resmi baru yang tidak tersedia di data.\n"
            ."- Jika pertanyaan di luar ruang lingkup sekolah/PPDB, jawab bahwa kamu hanya membantu pertanyaan seputar RA Fadhilah dan PPDB.\n"
            ."- Jangan menampilkan label internal seperti Pertanyaan, Jawaban, Kata kunci, atau relevansi.\n"
            ."- Ringkas, jelas, dan gunakan bahasa Indonesia.";

        $context = $this->knowledgeService->buildPromptContext($message);

        if ($context !== '') {
            $prompt .= "\n\n".$context;
        }

        return $prompt;
    }

    private function outOfScopeReply(): string
    {
        return 'Maaf, silakan ajukan pertanyaan mengenai RA Fadhilah atau penerimaan peserta didik baru di RA Fadhilah.';
    }
}
