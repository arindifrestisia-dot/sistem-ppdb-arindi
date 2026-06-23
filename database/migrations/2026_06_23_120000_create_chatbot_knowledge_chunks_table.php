<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_knowledge_chunks', function (Blueprint $table) {
            $table->id();
            $table->string('source_key')->unique();
            $table->string('source_type', 50);
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('title')->nullable();
            $table->longText('content');
            $table->longText('embedding')->nullable();
            $table->string('embedding_model', 100)->nullable();
            $table->string('content_hash', 64);
            $table->timestamp('indexed_at')->nullable();
            $table->timestamps();

            $table->index(['source_type', 'source_id']);
            $table->index('embedding_model');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_knowledge_chunks');
    }
};
