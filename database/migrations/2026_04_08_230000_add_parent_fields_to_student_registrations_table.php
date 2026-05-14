<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->string('father_name')->nullable()->after('medical_history');
            $table->string('father_birth_info')->nullable()->after('father_name');
            $table->string('father_job')->nullable()->after('father_birth_info');
            $table->string('father_education')->nullable()->after('father_job');
            $table->string('father_income')->nullable()->after('father_education');
            $table->string('father_phone', 30)->nullable()->after('father_income');
            $table->string('father_email')->nullable()->after('father_phone');
            $table->string('mother_name')->nullable()->after('father_email');
            $table->string('mother_birth_info')->nullable()->after('mother_name');
            $table->string('mother_job')->nullable()->after('mother_birth_info');
            $table->string('mother_education')->nullable()->after('mother_job');
            $table->string('mother_income')->nullable()->after('mother_education');
            $table->string('mother_phone', 30)->nullable()->after('mother_income');
            $table->string('mother_email')->nullable()->after('mother_phone');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'father_name',
                'father_birth_info',
                'father_job',
                'father_education',
                'father_income',
                'father_phone',
                'father_email',
                'mother_name',
                'mother_birth_info',
                'mother_job',
                'mother_education',
                'mother_income',
                'mother_phone',
                'mother_email',
            ]);
        });
    }
};
