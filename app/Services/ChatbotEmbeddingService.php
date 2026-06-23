<?php

namespace App\Services;

use App\Models\ChatbotKnowledgeChunk;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RuntimeException;

class ChatbotEmbeddingService
{
    public function embeddingModel(): string
    {
        return (string) config('services.ollama.embedding_model', 'embeddinggemma');
    }

    public function isReady(): bool
    {
        return Schema::hasTable('chatbot_knowledge_chunks')
            && trim($this->embeddingModel()) !== ''
            && trim((string) config('services.ollama.base_url')) !== '';
    }

    public function embed(string $text): array
    {
        $baseUrl = rtrim((string) config('services.ollama.base_url'), '/');
        $model = $this->embeddingModel();
        $timeout = (int) config('services.ollama.embedding_timeout', 60);

        if ($baseUrl === '' || $model === '') {
            throw new RuntimeException('Konfigurasi embedding Ollama belum lengkap.');
        }

        $response = Http::acceptJson()
            ->timeout($timeout)
            ->connectTimeout(10)
            ->post("{$baseUrl}/api/embeddings", [
                'model' => $model,
                'prompt' => Str::of($text)->squish()->limit(3000, '')->toString(),
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Ollama gagal membuat embedding: '.$response->body());
        }

        $embedding = $response->json('embedding');

        if (! is_array($embedding) || $embedding === []) {
            throw new RuntimeException('Respons embedding Ollama tidak dikenali.');
        }

        return array_map('floatval', $embedding);
    }

    public function indexChunks(array $chunks, bool $fresh = false): array
    {
        if (! $this->isReady()) {
            throw new RuntimeException('Tabel atau konfigurasi embedding belum tersedia.');
        }

        if ($fresh) {
            ChatbotKnowledgeChunk::query()->delete();
        }

        $stats = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
        ];

        foreach ($chunks as $chunk) {
            $content = Str::of((string) ($chunk['content'] ?? ''))->squish()->toString();

            if ($content === '') {
                continue;
            }

            $sourceKey = (string) $chunk['source_key'];
            $contentHash = hash('sha256', $content);
            $record = ChatbotKnowledgeChunk::query()->where('source_key', $sourceKey)->first();

            if (
                $record
                && $record->content_hash === $contentHash
                && $record->embedding_model === $this->embeddingModel()
                && filled($record->embedding)
            ) {
                $stats['skipped']++;
                continue;
            }

            $embedding = $this->embed($content);

            ChatbotKnowledgeChunk::query()->updateOrCreate(
                ['source_key' => $sourceKey],
                [
                    'source_type' => (string) ($chunk['source_type'] ?? 'manual'),
                    'source_id' => $chunk['source_id'] ?? null,
                    'title' => $chunk['title'] ?? null,
                    'content' => $content,
                    'embedding' => json_encode($embedding),
                    'embedding_model' => $this->embeddingModel(),
                    'content_hash' => $contentHash,
                    'indexed_at' => now(),
                ]
            );

            $stats[$record ? 'updated' : 'created']++;
        }

        return $stats;
    }

    public function search(string $query, int $limit = 5, ?float $minScore = null): array
    {
        if (! $this->isReady()) {
            return [];
        }

        $queryEmbedding = $this->embed($query);
        $threshold = $minScore ?? (float) config('services.ollama.rag_min_score', 0.15);

        return ChatbotKnowledgeChunk::query()
            ->where('embedding_model', $this->embeddingModel())
            ->whereNotNull('embedding')
            ->get()
            ->map(function (ChatbotKnowledgeChunk $chunk) use ($query, $queryEmbedding) {
                $embeddingScore = $this->cosineSimilarity($queryEmbedding, $chunk->embeddingVector());

                return [
                    'chunk' => $chunk,
                    'score' => $embeddingScore + $this->lexicalBoost($query, $chunk),
                    'embedding_score' => $embeddingScore,
                ];
            })
            ->filter(fn (array $result) => $result['score'] >= $threshold)
            ->sortByDesc('score')
            ->take($limit)
            ->values()
            ->all();
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        if ($a === [] || $b === [] || count($a) !== count($b)) {
            return 0.0;
        }

        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        foreach ($a as $index => $value) {
            $other = $b[$index];
            $dot += $value * $other;
            $normA += $value * $value;
            $normB += $other * $other;
        }

        if ($normA <= 0.0 || $normB <= 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    private function lexicalBoost(string $query, ChatbotKnowledgeChunk $chunk): float
    {
        $query = Str::of($query)->lower()->squish()->toString();
        $text = Str::of(trim(($chunk->title ?? '').' '.$chunk->content))->lower()->squish()->toString();

        $boost = 0.0;

        foreach (['daftar ulang', 'formulir', 'cash', 'transfer', 'dana', 'wawancara', 'seleksi', 'biaya'] as $phrase) {
            if (Str::contains($query, $phrase) && Str::contains($text, $phrase)) {
                $boost += 0.12;
            }
        }

        $queryWords = collect(explode(' ', $query))
            ->filter(fn (string $word) => strlen($word) >= 4)
            ->unique();

        $boost += $queryWords
            ->filter(fn (string $word) => Str::contains($text, $word))
            ->count() * 0.015;

        return min($boost, 0.35);
    }
}
