<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('role', 'parent')->update(['role' => 'orang_tua']);
        DB::table('users')->where('role', 'panitia')->update(['role' => 'panitia_ppdb']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role VARCHAR(30) NOT NULL DEFAULT 'orang_tua'");
        }
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'orang_tua')->update(['role' => 'parent']);
        DB::table('users')->where('role', 'panitia_ppdb')->update(['role' => 'panitia']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role VARCHAR(30) NOT NULL DEFAULT 'parent'");
        }
    }
};
