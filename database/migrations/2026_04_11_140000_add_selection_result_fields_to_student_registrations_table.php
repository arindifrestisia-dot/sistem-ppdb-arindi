<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->string('selection_result', 30)->nullable()->after('interview_selected_at');
            $table->timestamp('selection_published_at')->nullable()->after('selection_result');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'selection_result',
                'selection_published_at',
            ]);
        });
    }
};
