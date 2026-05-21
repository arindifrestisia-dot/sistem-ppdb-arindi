<?php

namespace App\Services;

use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransSnapService
{
    public function createFormPaymentTransaction(User $user, string $orderId, int $amount): array
    {
        return $this->createSnapTransaction([
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'item_details' => [[
                'id' => 'formulir-ppdb',
                'price' => $amount,
                'quantity' => 1,
                'name' => 'Formulir PPDB RA Fadhilah',
            ]],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'callbacks' => [
                'finish' => route('ortu.formulir'),
            ],
        ]);
    }

    public function createReRegistrationTransaction(StudentRegistration $registration, User $user, int $amount): array
    {
        return $this->createSnapTransaction([
            'transaction_details' => [
                'order_id' => $registration->reregistration_order_id,
                'gross_amount' => $amount,
            ],
            'item_details' => [[
                'id' => 'daftar-ulang',
                'price' => $amount,
                'quantity' => 1,
                'name' => 'Daftar Ulang PPDB RA Fadhilah',
            ]],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $registration->father_phone ?: $registration->mother_phone,
            ],
            'callbacks' => [
                'finish' => route('status-lulus'),
            ],
        ]);
    }

    public function getTransactionStatus(string $orderId): array
    {
        $response = $this->client()
            ->get($this->statusEndpoint($orderId));

        if (! $response->successful()) {
            throw new RuntimeException($response->json('status_message') ?: 'Gagal mengambil status transaksi Midtrans.');
        }

        return $response->json();
    }

    public function verifySignature(array $payload): bool
    {
        $signature = (string) ($payload['signature_key'] ?? '');

        if ($signature === '') {
            return false;
        }

        $serverKey = $this->serverKey();
        $plainText = (string) ($payload['order_id'] ?? '')
            . (string) ($payload['status_code'] ?? '')
            . (string) ($payload['gross_amount'] ?? '')
            . $serverKey;

        return hash_equals(hash('sha512', $plainText), $signature);
    }

    public function isConfigured(): bool
    {
        return $this->serverKey() !== '' && (string) config('services.midtrans.client_key') !== '';
    }

    private function client(): PendingRequest
    {
        if ($this->serverKey() === '') {
            throw new RuntimeException('Server key Midtrans belum diatur.');
        }

        return Http::withBasicAuth($this->serverKey(), '')
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('services.midtrans.timeout', 20));
    }

    private function createSnapTransaction(array $payload): array
    {
        $response = $this->client()
            ->post($this->snapEndpoint(), $payload);

        if (! $response->successful()) {
            throw new RuntimeException($response->json('error_messages.0') ?: 'Gagal membuat transaksi Midtrans.');
        }

        return $response->json();
    }

    private function snapEndpoint(): string
    {
        return $this->isProduction()
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    private function statusEndpoint(string $orderId): string
    {
        $baseUrl = $this->isProduction()
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';

        return $baseUrl . '/' . rawurlencode($orderId) . '/status';
    }

    private function serverKey(): string
    {
        return (string) config('services.midtrans.server_key');
    }

    private function isProduction(): bool
    {
        return filter_var(config('services.midtrans.is_production', false), FILTER_VALIDATE_BOOL);
    }
}
