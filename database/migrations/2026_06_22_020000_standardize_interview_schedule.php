<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('student_registrations')
            ->whereNotNull('interview_selected_at')
            ->update([
                'interview_time' => 'Silahkan datang ke sekolah RA FADHILAH pada jam 08.00 - 13.00',
                'interview_room' => 'RUANGAN TU',
            ]);
    }

    public function down(): void
    {
        // Jadwal lama tidak dikembalikan karena sesi sebelumnya tidak lagi digunakan.
    }
};
