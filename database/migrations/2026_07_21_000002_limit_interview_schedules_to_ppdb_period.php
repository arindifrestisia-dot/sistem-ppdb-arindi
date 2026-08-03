<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('interview_schedules')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            $ids = DB::table('interview_schedules')
                ->pluck('interview_date', 'id')
                ->filter(function (string $date) {
                    $date = Carbon::parse($date);

                    return $date->lt(Carbon::parse('2025-10-01'))
                        || $date->gt(Carbon::parse('2026-07-31'))
                        || $date->isSunday();
                })
                ->keys();

            if ($ids->isNotEmpty()) {
                DB::table('interview_schedules')->whereIn('id', $ids)->delete();
            }
        } else {
            DB::table('interview_schedules')
                ->where(function ($query) {
                    $query
                        ->whereDate('interview_date', '<', '2025-10-01')
                        ->orWhereDate('interview_date', '>', '2026-07-31')
                        ->orWhereRaw('DAYOFWEEK(interview_date) = 1');
                })
                ->delete();
        }

        $now = now();
        $date = Carbon::parse('2025-10-01');
        $end = Carbon::parse('2026-07-31');

        while ($date->lte($end)) {
            if ($date->isSunday()) {
                $date->addDay();
                continue;
            }

            $dateString = $date->toDateString();
            $exists = DB::table('interview_schedules')
                ->where('schedule_key', $dateString)
                ->exists();

            if (! $exists) {
                DB::table('interview_schedules')->insert([
                    'schedule_key' => $dateString,
                    'interview_date' => $dateString,
                    'session_label' => 'Jadwal Wawancara',
                    'interview_time' => 'Silahkan datang ke sekolah RA FADHILAH pada jam 08.00 - 13.00',
                    'room' => 'RUANGAN TU',
                    'sort_order' => 0,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $date->addDay();
        }
    }

    public function down(): void
    {
        //
    }
};
