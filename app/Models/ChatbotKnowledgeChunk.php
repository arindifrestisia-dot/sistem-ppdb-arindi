<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotKnowledgeChunk extends Model
{
    protected $fillable = [
        'source_key',
        'source_type',
        'source_id',
        'title',
        'content',
        'embedding',
        'embedding_model',
        'content_hash',
        'indexed_at',
    ];

    protected function casts(): array
    {
        return [
            'indexed_at' => 'datetime',
        ];
    }

    public function embeddingVector(): array
    {
        $decoded = json_decode((string) $this->embedding, true);

        return is_array($decoded) ? array_map('floatval', $decoded) : [];
    }
}
