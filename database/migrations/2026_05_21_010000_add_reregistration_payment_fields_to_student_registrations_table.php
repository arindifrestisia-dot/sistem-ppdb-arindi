<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->string('reregistration_order_id')->nullable()->unique()->after('selection_published_at');
            $table->string('reregistration_snap_token')->nullable()->after('reregistration_order_id');
            $table->string('reregistration_snap_redirect_url')->nullable()->after('reregistration_snap_token');
            $table->unsignedInteger('reregistration_amount')->nullable()->after('reregistration_snap_redirect_url');
            $table->string('reregistration_status', 40)->nullable()->after('reregistration_amount');
            $table->string('reregistration_payment_type', 60)->nullable()->after('reregistration_status');
            $table->timestamp('reregistration_paid_at')->nullable()->after('reregistration_payment_type');
            $table->json('reregistration_midtrans_payload')->nullable()->after('reregistration_paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'reregistration_order_id',
                'reregistration_snap_token',
                'reregistration_snap_redirect_url',
                'reregistration_amount',
                'reregistration_status',
                'reregistration_payment_type',
                'reregistration_paid_at',
                'reregistration_midtrans_payload',
            ]);
        });
    }
};
