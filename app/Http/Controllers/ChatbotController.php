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

        $baseUrl = rtrim((string) config('services.ollama.base_url'), '/');
        $model = (string) config('services.ollama.model');
        $systemPrompt = (string) config('services.ollama.system_prompt');
        $timeout = (int) config('services.ollama.timeout', 120);
        $keepAlive = (string) config('services.ollama.keep_alive', '10m');
        $numPredict = (int) config('services.ollama.num_predict', 256);

        if ($baseUrl === '' || $model === '') {
            return response()->json([
                'message' => 'Konfigurasi chatbot belum lengkap. Isi OLLAMA_BASE_URL dan OLLAMA_MODEL terlebih dahulu.',
            ], 500);
        }

        if (function_exists('set_time_limit')) {
            @set_time_limit($timeout + 5);
        }

        $payload = [
            'model' => $model,
            'prompt' => $validated['message'],
            'stream' => false,
            'keep_alive' => $keepAlive,
            'options' => [
                'num_predict' => $numPredict,
            ],
        ];

        if ($systemPrompt !== '') {
            $payload['system'] = $this->buildSystemPrompt($systemPrompt);
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

            return response()->json([
                'message' => 'Koneksi ke Ollama gagal atau waktunya habis. Coba lagi, ringkas pertanyaan, atau gunakan model yang lebih ringan.',
            ], 504);
        }

        if ($response->failed()) {
            report('Ollama request failed: '.$response->body());

            return response()->json([
                'message' => 'Chatbot sedang tidak bisa menjawab. Silakan coba beberapa saat lagi.',
            ], 502);
        }

        $data = $response->json();
        $reply = data_get($data, 'response');

        if (! is_string($reply) || trim($reply) === '') {
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

    private function buildSystemPrompt(string $basePrompt): string
    {
        $prompt = trim($basePrompt)."\n\n"
            .'Aturan tambahan:'."\n"
            ."- Jawab hanya berdasarkan pengetahuan sekolah yang diberikan dan data database yang tersedia.\n"
            ."- Jika data tidak tersedia, jangan mengarang.\n"
            ."- Untuk informasi yang belum ada, jawab: 'Maaf, saya belum memiliki data pasti untuk itu. Silakan hubungi pihak sekolah.'\n"
            ."- Ringkas, jelas, dan gunakan bahasa Indonesia.";

        $context = $this->knowledgeService->buildPromptContext();

        if ($context !== '') {
            $prompt .= "\n\n".$context;
        }

        return $prompt;
    }
}
