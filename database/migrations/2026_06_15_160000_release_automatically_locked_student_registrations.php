<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('student_registrations')
            ->whereNotNull('submitted_at')
            ->whereNotNull('locked_at')
            ->whereColumn('locked_at', 'submitted_at')
            ->update(['locked_at' => null]);
    }

    public function down(): void
    {
        // Automatically created locks cannot be distinguished after release.
    }
};
