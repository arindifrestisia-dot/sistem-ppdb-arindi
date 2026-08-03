<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->text('interview_notes')->nullable()->after('interview_completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn('interview_notes');
        });
    }
};
