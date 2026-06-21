<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('ppdb_form_payments')
            ->whereNull('paid_at')
            ->update([
                'amount' => 150000,
                'snap_token' => null,
                'snap_redirect_url' => null,
            ]);
    }

    public function down(): void
    {
        // Nominal transaksi tidak diturunkan kembali agar data pembayaran tetap konsisten.
    }
};
