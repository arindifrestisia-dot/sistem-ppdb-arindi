<?php

namespace App\Jobs;

use App\Models\PpdbNotificationLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        $authorization = $this->authorizationToken();
        $endpoint = '/' . ltrim((string) config('services.wablas.send_endpoint', '/api/send-message'), '/');

        if ($baseUrl === '' || $authorization === '') {
            $log->update([
                'status' => 'failed',
                'error' => 'Konfigurasi Wablas belum lengkap.',
            ]);

            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $authorization,
            ])->timeout((int) config('services.wablas.timeout', 15))
                ->asForm()
                ->post($baseUrl . $endpoint, [
                    'phone' => $log->recipient,
                    'message' => $this->messageBody($log),
                    'ref_id' => (string) $log->id,
                ]);

            if (! $response->successful() || $response->json('status') === false) {
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

            Log::error('Gagal mengirim pesan Wablas.', [
                'notification_log_id' => $log->id,
                'recipient' => $log->recipient,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function authorizationToken(): string
    {
        $token = trim((string) config('services.wablas.token'));
        $secretKey = trim((string) config('services.wablas.secret_key'));

        if ($token === '') {
            return '';
        }

        if ($secretKey === '' || str_contains($token, '.')) {
            return $token;
        }

        return $token . '.' . $secretKey;
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
