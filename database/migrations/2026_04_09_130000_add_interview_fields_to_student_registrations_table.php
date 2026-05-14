<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->string('interview_schedule_key')->nullable()->after('locked_at');
            $table->date('interview_date')->nullable()->after('interview_schedule_key');
            $table->string('interview_day_name', 30)->nullable()->after('interview_date');
            $table->string('interview_time', 100)->nullable()->after('interview_day_name');
            $table->string('interview_room', 100)->nullable()->after('interview_time');
            $table->timestamp('interview_selected_at')->nullable()->after('interview_room');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'interview_schedule_key',
                'interview_date',
                'interview_day_name',
                'interview_time',
                'interview_room',
                'interview_selected_at',
            ]);
        });
    }
};
