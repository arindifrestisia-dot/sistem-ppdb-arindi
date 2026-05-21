<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_registration_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('notification_key', 100);
            $table->string('channel', 30);
            $table->string('recipient');
            $table->string('subject');
            $table->text('message');
            $table->string('deduplication_key')->nullable();
            $table->string('status', 30)->default('pending');
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['notification_key', 'channel']);
            $table->unique(['channel', 'recipient', 'deduplication_key'], 'ppdb_notification_dedup_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_notification_logs');
    }
};
