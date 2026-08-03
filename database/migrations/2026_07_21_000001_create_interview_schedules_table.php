<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_key')->unique();
            $table->date('interview_date');
            $table->string('session_label')->default('Jadwal Wawancara');
            $table->string('interview_time', 100);
            $table->string('room', 100);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();
        $start = Carbon::create(2025, 10, 1);
        $end = Carbon::create(2026, 7, 31);
        $rows = [];

        while ($start->lte($end)) {
            if ($start->isSunday()) {
                $start->addDay();
                continue;
            }

            $date = $start->toDateString();
            $rows[] = [
                'schedule_key' => $date,
                'interview_date' => $date,
                'session_label' => 'Jadwal Wawancara',
                'interview_time' => 'Silahkan datang ke sekolah RA FADHILAH pada jam 08.00 - 13.00',
                'room' => 'RUANGAN TU',
                'sort_order' => 0,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $start->addDay();
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('interview_schedules')->insert($chunk);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_schedules');
    }
};
