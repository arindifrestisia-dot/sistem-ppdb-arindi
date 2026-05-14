<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_contents', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30);
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('image_path')->nullable();
            $table->date('published_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['type', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_contents');
    }
};
