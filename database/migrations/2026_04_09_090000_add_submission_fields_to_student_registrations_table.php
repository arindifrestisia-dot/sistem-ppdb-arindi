<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->string('registration_number')->nullable()->unique()->after('mother_email');
            $table->timestamp('submitted_at')->nullable()->after('registration_number');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropUnique(['registration_number']);
            $table->dropColumn(['registration_number', 'submitted_at']);
        });
    }
};
