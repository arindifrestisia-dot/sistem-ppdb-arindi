<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('nickname');
            $table->string('gender', 20);
            $table->string('birth_place');
            $table->date('birth_date');
            $table->decimal('weight_kg', 5, 2);
            $table->decimal('height_cm', 5, 2);
            $table->text('home_address');
            $table->string('origin_region');
            $table->string('citizenship', 10);
            $table->boolean('special_needs')->default(false);
            $table->unsignedInteger('child_order');
            $table->unsignedInteger('siblings_total');
            $table->text('medical_history')->nullable();
            $table->string('child_photo_path')->nullable();
            $table->string('parents_id_card_path')->nullable();
            $table->string('birth_certificate_path')->nullable();
            $table->string('family_card_path')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_registrations');
    }
};
