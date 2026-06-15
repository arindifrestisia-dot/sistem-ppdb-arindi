<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_form_fields', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('field_key')->unique();
            $table->string('type', 30);
            $table->string('section', 30);
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('student_registrations', function (Blueprint $table) {
            $table->json('custom_form_data')->nullable()->after('family_card_path');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn('custom_form_data');
        });

        Schema::dropIfExists('parent_form_fields');
    }
};
