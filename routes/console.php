<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\StudentRegistration;
use App\Services\PpdbNotificationService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('ppdb:send-reminders', function (PpdbNotificationService $notifications) {
    foreach ([3 => 'interview_reminder_h3', 1 => 'interview_reminder_h1'] as $days => $notificationKey) {
        StudentRegistration::query()
            ->with('user')
            ->whereNotNull('interview_selected_at')
            ->whereDate('interview_date', now()->copy()->addDays($days)->toDateString())
            ->chunkById(100, function ($registrations) use ($notifications, $notificationKey, $days) {
                foreach ($registrations as $registration) {
                    $notifications->send($notificationKey, $registration->user, $registration, [
                        'deduplication_suffix' => now()->toDateString() . ':h-' . $days,
                    ]);
                }
            });
    }

    foreach ([7, 3, 1] as $daysLeft) {
        StudentRegistration::query()
            ->with('user')
            ->where('selection_result', 'lulus')
            ->whereNotNull('selection_published_at')
            ->get()
            ->filter(function (StudentRegistration $registration) use ($daysLeft) {
                $deadline = $registration->selection_published_at
                    ->copy()
                    ->addDays((int) config('ppdb_notifications.deadlines.re_registration_days', 7))
                    ->startOfDay();

                return (int) now()->copy()->startOfDay()->diffInDays($deadline, false) === $daysLeft;
            })
            ->each(function (StudentRegistration $registration) use ($notifications, $daysLeft) {
                $notifications->send('re_registration_deadline_reminder', $registration->user, $registration, [
                    'days_left' => $daysLeft,
                    'deduplication_suffix' => now()->toDateString() . ':h-' . $daysLeft,
                ]);
            });
    }

    $this->info('Reminder PPDB selesai diproses.');
})->purpose('Send PPDB interview and re-registration reminders');

Schedule::command('ppdb:send-reminders')->dailyAt('08:00');
