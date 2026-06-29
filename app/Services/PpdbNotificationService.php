<?php

namespace App\Services;

use App\Jobs\SendFonnteMessage;
use App\Mail\PpdbNotificationMail;
use App\Models\PpdbNotificationLog;
use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class PpdbNotificationService
{
    public function send(string $key, ?User $user = null, ?StudentRegistration $registration = null, array $data = []): void
    {
        if (! config('ppdb_notifications.enabled')) {
            return;
        }

        $template = config("ppdb_notifications.templates.$key");

        if (! is_array($template)) {
            Log::warning('Template notifikasi PPDB tidak ditemukan.', ['key' => $key]);

            return;
        }

        $registration ??= $user?->studentRegistration;

        if ($registration && ! $registration->relationLoaded('user')) {
            $registration->loadMissing('user');
        }

        $user ??= $registration?->user;
        $context = $this->buildContext($user, $registration, $data);
        $subject = $this->render($template['subject'], $context);
        $message = $this->render($template['message'], $context);
        $deduplicationKey = $data['deduplication_key'] ?? $this->deduplicationKey($key, $user, $registration, $data);

        if (config('ppdb_notifications.channels.mail')) {
            foreach ($this->mailRecipients($user, $registration) as $email) {
                $this->sendMail($email, $subject, $message, $key, $deduplicationKey, $user, $registration);
            }
        }

        if (config('ppdb_notifications.channels.fonnte')) {
            foreach ($this->whatsappRecipients($registration) as $phone) {
                $this->sendFonnte($phone, $subject, $message, $key, $deduplicationKey, $user, $registration);
            }
        }
    }

    private function sendMail(string $email, string $subject, string $message, string $key, string $deduplicationKey, ?User $user, ?StudentRegistration $registration): void
    {
        if ($this->alreadySent('mail', $email, $deduplicationKey)) {
            return;
        }

        $log = $this->createLog('mail', $email, $subject, $message, $key, $deduplicationKey, $user, $registration);

        try {
            Mail::to($email)->queue(new PpdbNotificationMail($subject, $message));

            $log->update([
                'status' => 'queued',
            ]);
        } catch (Throwable $exception) {
            $log->update([
                'status' => 'failed',
                'error' => $exception->getMessage(),
            ]);

            Log::error('Gagal mengirim email notifikasi PPDB.', [
                'recipient' => $email,
                'key' => $key,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function sendFonnte(string $phone, string $subject, string $message, string $key, string $deduplicationKey, ?User $user, ?StudentRegistration $registration): void
    {
        if ($this->alreadySent('fonnte', $phone, $deduplicationKey)) {
            return;
        }

        $log = $this->createLog('fonnte', $phone, $subject, $message, $key, $deduplicationKey, $user, $registration);

        $log->update([
            'status' => 'queued',
        ]);

        SendFonnteMessage::dispatch($log->id);
    }

    private function createLog(string $channel, string $recipient, string $subject, string $message, string $key, string $deduplicationKey, ?User $user, ?StudentRegistration $registration): PpdbNotificationLog
    {
        return PpdbNotificationLog::updateOrCreate([
            'channel' => $channel,
            'recipient' => $recipient,
            'deduplication_key' => $deduplicationKey,
        ], [
            'student_registration_id' => $registration?->id,
            'user_id' => $user?->id,
            'notification_key' => $key,
            'subject' => $subject,
            'message' => $message,
            'status' => 'pending',
            'error' => null,
            'sent_at' => null,
        ]);
    }

    private function alreadySent(string $channel, string $recipient, string $deduplicationKey): bool
    {
        return PpdbNotificationLog::query()
            ->where('channel', $channel)
            ->where('recipient', $recipient)
            ->where('deduplication_key', $deduplicationKey)
            ->whereIn('status', ['pending', 'queued', 'sent'])
            ->exists();
    }

    private function buildContext(?User $user, ?StudentRegistration $registration, array $data): array
    {
        $selectionPublishedAt = $registration?->selection_published_at
            ? Carbon::parse($registration->selection_published_at)
            : null;
        $deadline = $data['re_registration_deadline'] ?? $selectionPublishedAt?->copy()
            ->addDays((int) config('ppdb_notifications.deadlines.re_registration_days', 7));

        if ($deadline instanceof Carbon) {
            $deadline = $deadline->translatedFormat('d F Y');
        }

        return array_merge([
            'parent_name' => $user?->name ?? 'Bapak/Ibu',
            'student_name' => $registration?->full_name ?? 'Ananda',
            'registration_number' => $registration?->registration_number ?? '-',
            'interview_date' => $registration?->interview_date ? Carbon::parse($registration->interview_date)->translatedFormat('d F Y') : '-',
            'interview_time' => $registration?->interview_time ?? '-',
            'interview_room' => $registration?->interview_room ?? '-',
            'selection_date' => $selectionPublishedAt?->translatedFormat('d F Y') ?? '-',
            'form_amount' => $this->formatCurrency((int) config('ppdb_notifications.amounts.form')),
            're_registration_amount' => $this->formatCurrency((int) config('ppdb_notifications.amounts.re_registration')),
            'payment_amount' => $data['payment_amount'] ?? $this->formatCurrency((int) ($registration?->reregistration_amount ?: config('ppdb_notifications.amounts.re_registration'))),
            'payment_method' => $data['payment_method'] ?? $this->paymentMethodLabel($registration?->reregistration_payment_type),
            'installment_label' => $data['installment_label'] ?? '-',
            'installment_amount' => $data['installment_amount'] ?? '-',
            'installment_deadline' => $data['installment_deadline'] ?? ($deadline ?? '-'),
            'bank_name' => config('ppdb_notifications.bank.bank_name'),
            'bank_account_number' => config('ppdb_notifications.bank.account_number'),
            'bank_account_name' => config('ppdb_notifications.bank.account_name'),
            're_registration_deadline' => $deadline ?? '-',
            'academic_year' => $this->academicYear($registration),
            'days_left' => $data['days_left'] ?? '-',
        ], $data);
    }

    private function render(string $text, array $context): string
    {
        foreach ($context as $key => $value) {
            if (is_scalar($value) || $value === null) {
                $text = str_replace('{' . $key . '}', (string) $value, $text);
            }
        }

        return $text;
    }

    private function mailRecipients(?User $user, ?StudentRegistration $registration): array
    {
        return collect([
            $user?->email,
            $registration?->father_email,
            $registration?->mother_email,
        ])->filter()
            ->map(fn (string $email) => Str::lower(trim($email)))
            ->filter(fn (string $email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }

    private function whatsappRecipients(?StudentRegistration $registration): array
    {
        return collect([
            $registration?->father_phone,
            $registration?->mother_phone,
        ])->filter()
            ->map(fn (string $phone) => $this->normalizePhone($phone))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function normalizePhone(string $phone): ?string
    {
        $phone = preg_replace('/\D+/', '', $phone) ?? '';

        if ($phone === '') {
            return null;
        }

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '8')) {
            return '62' . $phone;
        }

        return $phone;
    }

    private function deduplicationKey(string $key, ?User $user, ?StudentRegistration $registration, array $data): string
    {
        return implode(':', array_filter([
            $key,
            $registration?->id ? 'registration-' . $registration->id : null,
            ! $registration?->id && $user?->id ? 'user-' . $user->id : null,
            $data['deduplication_suffix'] ?? null,
        ]));
    }

    private function formatCurrency(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    private function paymentMethodLabel(?string $paymentType): string
    {
        return match ($paymentType) {
            'manual_transfer' => 'Transfer BRI / DANA',
            'manual_cash' => 'Cash ke Sekolah',
            'midtrans' => 'Midtrans',
            default => $paymentType ? Str::headline(str_replace('_', ' ', $paymentType)) : '-',
        };
    }

    private function academicYear(?StudentRegistration $registration): string
    {
        $date = $registration?->submitted_at ?? $registration?->created_at ?? now();
        $year = (int) Carbon::parse($date)->format('Y');

        if ((int) Carbon::parse($date)->format('n') < 7) {
            return ($year - 1) . '/' . $year;
        }

        return $year . '/' . ($year + 1);
    }
}
