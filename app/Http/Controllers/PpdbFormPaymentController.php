<?php

namespace App\Http\Controllers;

use App\Models\PpdbFormPayment;
use App\Services\MidtransSnapService;
use App\Services\PpdbNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class PpdbFormPaymentController extends Controller
{
    public function __construct(
        private readonly MidtransSnapService $midtrans,
        private readonly PpdbNotificationService $notifications,
    ) {
    }

    public function show(Request $request): View|RedirectResponse
    {
        if ($request->user()?->isStaff()) {
            return redirect()->route('dashboard');
        }

        $payment = $request->user()->ppdbFormPayment()->first();
        $request->user()->setRelation('ppdbFormPayment', $payment);

        return view('dashboard.panel-ortu.formulir', [
            'payment' => $payment,
            'isPaid' => (bool) $payment?->isPaid(),
            'formAmount' => (int) config('ppdb_notifications.amounts.form', 150000),
            'formAmountLabel' => $this->formatCurrency((int) config('ppdb_notifications.amounts.form', 150000)),
            'midtransClientKey' => (string) config('services.midtrans.client_key'),
            'isMidtransConfigured' => $this->midtrans->isConfigured(),
        ]);
    }

    public function createToken(Request $request): JsonResponse
    {
        if ($request->user()?->isStaff()) {
            return response()->json(['message' => 'Akses staff tidak dapat membeli formulir.'], 403);
        }

        if ($request->user()->hasPaidPpdbForm()) {
            return response()->json(['status' => 'paid', 'message' => 'Pembayaran formulir sudah lunas.']);
        }

        if ($request->user()->ppdbFormPayment?->status === 'manual_pending') {
            return response()->json(['message' => 'Pembayaran sedang menunggu verifikasi panitia.'], 422);
        }

        if (! $this->midtrans->isConfigured()) {
            return response()->json(['message' => 'Konfigurasi Midtrans sandbox belum lengkap.'], 422);
        }

        $amount = (int) config('ppdb_notifications.amounts.form', 150000);
        $payment = $request->user()->ppdbFormPayment;

        if (! $payment || in_array($payment->status, ['deny', 'cancel', 'expire', 'failure'], true)) {
            $payment = PpdbFormPayment::create([
                'user_id' => $request->user()->id,
                'order_id' => $this->generateOrderId(),
                'amount' => $amount,
                'status' => 'pending',
            ]);
        }

        if ($payment->snap_token && $payment->status === 'pending') {
            return response()->json([
                'status' => 'pending',
                'snap_token' => $payment->snap_token,
            ]);
        }

        try {
            $transaction = $this->midtrans->createFormPaymentTransaction($request->user(), $payment->order_id, $amount);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        $payment->forceFill([
            'snap_token' => (string) ($transaction['token'] ?? ''),
            'snap_redirect_url' => $transaction['redirect_url'] ?? null,
            'status' => 'pending',
            'payment_type' => 'midtrans',
            'midtrans_payload' => $transaction,
        ])->save();

        $request->user()->setRelation('ppdbFormPayment', $payment);

        return response()->json([
            'status' => 'pending',
            'snap_token' => $payment->snap_token,
            'redirect_url' => $payment->snap_redirect_url,
        ]);
    }

    public function submitManual(Request $request): RedirectResponse
    {
        if ($request->user()?->isStaff()) {
            abort(403);
        }

        if ($request->user()->hasPaidPpdbForm()) {
            return back()->with('status', 'Pembayaran formulir sudah lunas.');
        }

        if ($request->user()->ppdbFormPayment?->status === 'manual_pending') {
            return back()->with('status', 'Bukti pembayaran sudah dikirim dan sedang menunggu verifikasi panitia.');
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:transfer,cash'],
            'proof' => ['nullable', 'required_if:payment_method,transfer', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'proof.required_if' => 'Bukti pembayaran wajib diunggah untuk metode transfer atau DANA.',
            'proof.mimes' => 'Bukti pembayaran harus berupa JPG, PNG, atau PDF.',
            'proof.max' => 'Ukuran bukti pembayaran maksimal 5 MB.',
        ]);

        $payment = $request->user()->ppdbFormPayment;
        $oldProof = $payment?->proof_path;
        $proofPath = $request->hasFile('proof')
            ? $request->file('proof')->store('payment-proofs/form', 'public')
            : $oldProof;

        if (! $payment) {
            $payment = new PpdbFormPayment([
                'user_id' => $request->user()->id,
                'order_id' => $this->generateOrderId(),
                'amount' => (int) config('ppdb_notifications.amounts.form', 150000),
            ]);
        }

        $payment->forceFill([
            'status' => 'manual_pending',
            'payment_type' => 'manual_' . $validated['payment_method'],
            'proof_path' => $proofPath,
            'paid_at' => null,
            'verified_by' => null,
            'verified_at' => null,
        ])->save();

        $request->user()->setRelation('ppdbFormPayment', $payment);

        if ($request->hasFile('proof') && $oldProof && $oldProof !== $proofPath) {
            Storage::disk('public')->delete($oldProof);
        }

        return back()->with('status', 'Pembayaran berhasil dikirim dan sedang menunggu verifikasi panitia.');
    }

    public function sync(Request $request): JsonResponse
    {
        if ($request->user()->hasPaidPpdbForm()) {
            return response()->json([
                'status' => 'settlement',
                'paid' => true,
            ]);
        }

        $payment = $request->user()->ppdbFormPayment;

        if (! $payment) {
            return response()->json(['message' => 'Transaksi pembelian formulir tidak ditemukan.'], 404);
        }

        try {
            $payload = $this->midtrans->getTransactionStatus($payment->order_id);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        $this->applyStatus($payment, $payload);
        $payment->refresh();

        return response()->json([
            'status' => $payment->status,
            'paid' => $payment->isPaid(),
        ]);
    }

    public function notification(Request $request): JsonResponse
    {
        $payload = $request->all();

        if (! $this->midtrans->verifySignature($payload)) {
            return response()->json(['message' => 'Signature Midtrans tidak valid.'], 403);
        }

        $payment = PpdbFormPayment::where('order_id', $payload['order_id'] ?? null)->first();

        if (! $payment) {
            return response()->json(['message' => 'Transaksi pembelian formulir tidak ditemukan.'], 404);
        }

        $this->applyStatus($payment, $payload);

        return response()->json(['message' => 'OK']);
    }

    private function applyStatus(PpdbFormPayment $payment, array $payload): void
    {
        $wasPaid = $payment->isPaid();
        $transactionStatus = (string) ($payload['transaction_status'] ?? $payment->status ?? 'pending');
        $fraudStatus = $payload['fraud_status'] ?? null;
        $isPaid = $transactionStatus === 'settlement'
            || ($transactionStatus === 'capture' && in_array($fraudStatus, [null, 'accept'], true));

        $payment->forceFill([
            'status' => $transactionStatus,
            'payment_type' => $payload['payment_type'] ?? $payment->payment_type,
            'midtrans_payload' => $payload,
        ]);

        if ($isPaid && ! $payment->paid_at) {
            $payment->paid_at = $this->parseMidtransDate(
                $payload['settlement_time'] ?? $payload['transaction_time'] ?? null
            );
        }

        $payment->save();

        if ($isPaid && ! $wasPaid) {
            $this->notifications->send('form_payment_approved', $payment->user);
        }
    }

    private function generateOrderId(): string
    {
        do {
            $orderId = 'FORM-' . now()->format('Ymd') . '-' . Str::upper(Str::random(8));
        } while (PpdbFormPayment::where('order_id', $orderId)->exists());

        return $orderId;
    }

    private function parseMidtransDate(?string $date): Carbon
    {
        if (! $date) {
            return now();
        }

        return Carbon::parse($date);
    }

    private function formatCurrency(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
