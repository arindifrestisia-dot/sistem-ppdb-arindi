<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->string('religion', 30)->nullable()->after('birth_date');
            $table->string('child_status', 30)->nullable()->after('special_needs');
            $table->text('special_needs_description')->nullable()->after('special_needs');
            $table->string('blood_type', 20)->nullable()->after('child_status');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'religion',
                'special_needs_description',
                'child_status',
                'blood_type',
            ]);
        });
    }
};
