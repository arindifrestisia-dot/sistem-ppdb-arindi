<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_form_payments', function (Blueprint $table) {
            $table->string('proof_path')->nullable()->after('payment_type');
            $table->foreignId('verified_by')->nullable()->after('paid_at')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });

        Schema::table('student_registrations', function (Blueprint $table) {
            $table->string('reregistration_proof_path')->nullable()->after('reregistration_payment_type');
            $table->foreignId('reregistration_verified_by')->nullable()->after('reregistration_paid_at')->constrained('users')->nullOnDelete();
            $table->timestamp('reregistration_verified_at')->nullable()->after('reregistration_verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_form_payments', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['proof_path', 'verified_by', 'verified_at']);
        });

        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropForeign(['reregistration_verified_by']);
            $table->dropColumn(['reregistration_proof_path', 'reregistration_verified_by', 'reregistration_verified_at']);
        });
    }
};
