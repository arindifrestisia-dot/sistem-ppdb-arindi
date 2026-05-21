<?php

namespace App\Jobs;

use App\Models\PpdbNotificationLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Throwable;

class SendWablasMessage implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $notificationLogId,
    ) {
    }

    public function handle(): void
    {
        $log = PpdbNotificationLog::find($this->notificationLogId);

        if (! $log) {
            return;
        }

        $baseUrl = rtrim((string) config('services.wablas.base_url'), '/');
        $token = (string) config('services.wablas.token');

        if ($baseUrl === '' || $token === '') {
            $log->update([
                'status' => 'failed',
                'error' => 'Konfigurasi Wablas belum lengkap.',
            ]);

            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->timeout((int) config('services.wablas.timeout', 15))
                ->asForm()
                ->post($baseUrl . '/api/send-message', [
                    'phone' => $log->recipient,
                    'message' => $log->message,
                ]);

            if (! $response->successful()) {
                $log->update([
                    'status' => 'failed',
                    'error' => $response->body(),
                ]);

                return;
            }

            $log->update([
                'status' => 'sent',
                'error' => null,
                'sent_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $log->update([
                'status' => 'failed',
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}
