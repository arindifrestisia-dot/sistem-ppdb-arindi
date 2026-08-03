<?php

namespace App\Http\Controllers;

use App\Services\ChatbotKnowledgeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ChatbotController extends Controller
{
    public function __construct(
        private readonly ChatbotKnowledgeService $knowledgeService
    ) {}

    public function message(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);
        $startedAt = microtime(true);

        if ($directAnswer = $this->knowledgeService->findDirectAnswer($validated['message'])) {
            return $this->chatbotResponse($request, $validated['message'], [
                'reply' => $directAnswer,
                'source' => 'knowledge',
            ], 200, $startedAt);
        }

        if ($dataAnswer = $this->knowledgeService->findDataBackedAnswer($validated['message'])) {
            return $this->chatbotResponse($request, $validated['message'], [
                'reply' => $dataAnswer,
                'source' => 'database',
            ], 200, $startedAt);
        }

        if (! $this->knowledgeService->isSchoolScope($validated['message'])) {
            return $this->chatbotResponse($request, $validated['message'], [
                'reply' => $this->outOfScopeReply(),
                'source' => 'scope',
            ], 200, $startedAt);
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
                return $this->chatbotResponse($request, $validated['message'], [
                    'reply' => $fallbackAnswer,
                    'source' => 'fallback',
                ], 200, $startedAt);
            }

            return $this->chatbotResponse($request, $validated['message'], [
                'message' => 'Konfigurasi chatbot belum lengkap. Isi OLLAMA_BASE_URL dan OLLAMA_MODEL terlebih dahulu.',
            ], 500, $startedAt);
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
                return $this->chatbotResponse($request, $validated['message'], [
                    'reply' => $fallbackAnswer,
                    'source' => 'fallback',
                ], 200, $startedAt);
            }

            return $this->chatbotResponse($request, $validated['message'], [
                'message' => 'Koneksi ke Ollama gagal atau waktunya habis. Coba lagi, ringkas pertanyaan, atau gunakan model yang lebih ringan.',
            ], 504, $startedAt);
        }

        if ($response->failed()) {
            report('Ollama request failed: '.$response->body());

            if ($fallbackAnswer = $this->knowledgeService->buildScopedFallbackAnswer($validated['message'])) {
                return $this->chatbotResponse($request, $validated['message'], [
                    'reply' => $fallbackAnswer,
                    'source' => ' fallback',
                ], 200, $startedAt);
            }

            return $this->chatbotResponse($request, $validated['message'], [
                'message' => 'Chatbot sedang tidak bisa menjawab. Silakan coba beberapa saat lagi.',
            ], 502, $startedAt);
        }

        $data = $response->json();
        $reply = data_get($data, 'response');

        if (! is_string($reply) || trim($reply) === '') {
            if ($fallbackAnswer = $this->knowledgeService->buildScopedFallbackAnswer($validated['message'])) {
                return $this->chatbotResponse($request, $validated['message'], [
                    'reply' => $fallbackAnswer,
                    'source' => 'fallback',
                ], 200, $startedAt);
            }

            return $this->chatbotResponse($request, $validated['message'], [
                'message' => 'Ollama merespons, tetapi format jawabannya belum dikenali.',
                'raw' => $data,
            ], 502, $startedAt);
        }

        return $this->chatbotResponse($request, $validated['message'], [
            'reply' => $reply,
            'source' => 'ollama',
        ], 200, $startedAt);
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

    private function chatbotResponse(Request $request, string $question, array $payload, int $status, float $startedAt): JsonResponse
    {
        $durationSeconds = round(microtime(true) - $startedAt, 3);

        if (isset($payload['reply']) && is_string($payload['reply'])) {
            $payload['reply'] = $this->cleanChatbotReply($payload['reply']);
        }

        Log::channel('chatbot')->info('Chatbot message answered', [
            'question' => $question,
            'answer' => $payload['reply'] ?? null,
            'error_message' => $payload['message'] ?? null,
            'source' => $payload['source'] ?? 'error',
            'duration_seconds' => $durationSeconds,
            'status_code' => $status,
            'user_id' => $request->user()?->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json($payload, $status);
    }

    private function cleanChatbotReply(string $reply): string
    {
        $reply = trim($reply);

        if (preg_match('/(?:^|\s)Jawaban\s*:\s*(.+)$/isu', $reply, $matches) === 1) {
            $reply = trim($matches[1]);
        }

        $reply = preg_replace('/^(Pembukaan|Pertanyaan|Jawaban)\s*:\s*/iu', '', $reply) ?? $reply;

        return trim($reply);
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
            .'- Ringkas, jelas, dan gunakan bahasa Indonesia.';

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
