<?php

namespace App\Jobs;

use App\Models\PpdbNotificationLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendFonnteMessage implements ShouldQueue
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

        $baseUrl = rtrim((string) config('services.fonnte.base_url'), '/');
        $authorization = trim((string) config('services.fonnte.token'));
        $endpoint = '/' . ltrim((string) config('services.fonnte.send_endpoint', '/send'), '/');

        if ($baseUrl === '' || $authorization === '') {
            $log->update([
                'status' => 'failed',
                'error' => 'Konfigurasi Fonnte belum lengkap.',
            ]);

            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $authorization,
            ])->timeout((int) config('services.fonnte.timeout', 15))
                ->asForm()
                ->post($baseUrl . $endpoint, [
                    'target' => $log->recipient,
                    'message' => $this->messageBody($log),
                    'countryCode' => (string) config('services.fonnte.country_code', '62'),
                ]);

            $responseStatus = $response->json('status', $response->json('Status'));

            if (! $response->successful() || $responseStatus === false) {
                $log->update([
                    'status' => 'failed',
                    'error' => $response->json('reason') ?: $response->json('detail') ?: $response->body(),
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

            Log::error('Gagal mengirim pesan Fonnte.', [
                'notification_log_id' => $log->id,
                'recipient' => $log->recipient,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function messageBody(PpdbNotificationLog $log): string
    {
        $message = collect([
            $log->subject,
            $log->message,
            config('ppdb_notifications.footer', 'RA Fadhilah'),
        ])->filter(fn (?string $line) => filled($line))
            ->implode("\n\n");

        return mb_strlen($message) > 1024
            ? mb_substr($message, 0, 1021) . '...'
            : $message;
    }
}
