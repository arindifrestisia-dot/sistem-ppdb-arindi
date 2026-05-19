<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->string('father_religion', 30)->nullable()->after('father_birth_info');
            $table->string('father_citizenship', 10)->nullable()->after('father_religion');
            $table->string('father_status', 30)->nullable()->after('father_citizenship');
            $table->text('father_address')->nullable()->after('father_phone');
            $table->string('mother_religion', 30)->nullable()->after('mother_birth_info');
            $table->string('mother_citizenship', 10)->nullable()->after('mother_religion');
            $table->string('mother_status', 30)->nullable()->after('mother_citizenship');
            $table->text('mother_address')->nullable()->after('mother_phone');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'father_religion',
                'father_citizenship',
                'father_status',
                'father_address',
                'mother_religion',
                'mother_citizenship',
                'mother_status',
                'mother_address',
            ]);
        });
    }
};
