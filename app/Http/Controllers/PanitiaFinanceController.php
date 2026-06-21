<?php

namespace App\Http\Controllers;

use App\Models\PpdbFormPayment;
use App\Models\StudentRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\PpdbNotificationService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PanitiaFinanceController extends Controller
{
    private const FORM_PAYMENT_AMOUNT = 150000;
    private const REREGISTRATION_AMOUNT = 2500000;

    public function __construct(private readonly PpdbNotificationService $notifications)
    {
    }

    public function verifyFormPayment(Request $request, PpdbFormPayment $payment): RedirectResponse
    {
        abort_unless(str_starts_with((string) $payment->payment_type, 'manual_'), 422);

        if (! $payment->isPaid()) {
            $payment->forceFill([
                'status' => 'settlement',
                'paid_at' => now(),
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ])->save();

            $this->notifications->send('form_payment_approved', $payment->user);
        }

        return back()->with('status', 'Pembayaran formulir berhasil diverifikasi.');
    }

    public function formPaymentProof(PpdbFormPayment $payment): BinaryFileResponse
    {
        abort_unless($payment->proof_path && Storage::disk('public')->exists($payment->proof_path), 404);

        return response()->file(Storage::disk('public')->path($payment->proof_path));
    }

    public function verifyReRegistration(Request $request, StudentRegistration $registration): RedirectResponse
    {
        abort_unless($registration->selection_result === 'lulus', 404);
        abort_if($registration->reregistration_payment_type === 'midtrans', 422);

        if (! $registration->reregistration_paid_at) {
            $registration->forceFill([
                'reregistration_status' => 'settlement',
                'reregistration_payment_type' => $registration->reregistration_payment_type ?: 'manual_cash',
                'reregistration_amount' => $registration->reregistration_amount ?: (int) config('ppdb_notifications.amounts.re_registration', self::REREGISTRATION_AMOUNT),
                'reregistration_paid_at' => now(),
                'reregistration_verified_by' => $request->user()->id,
                'reregistration_verified_at' => now(),
            ])->save();

            $this->notifications->send('re_registration_approved', $registration->user, $registration);
            $this->notifications->send('student_officially_registered', $registration->user, $registration);
        }

        return back()->with('status', 'Pembayaran daftar ulang berhasil diverifikasi.');
    }

    public function reRegistrationProof(StudentRegistration $registration): BinaryFileResponse
    {
        abort_unless(
            $registration->reregistration_proof_path
                && Storage::disk('public')->exists($registration->reregistration_proof_path),
            404
        );

        return response()->file(Storage::disk('public')->path($registration->reregistration_proof_path));
    }

    public function formPayments(Request $request): View
    {
        Carbon::setLocale('id');

        $search = trim((string) $request->string('q'));
        $status = (string) $request->string('status');
        $academicYear = (string) $request->string('ta');

        $allPayments = PpdbFormPayment::query()
            ->with('user.studentRegistration')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (PpdbFormPayment $payment) => $this->decorateFormPayment($payment));

        $filteredPayments = $allPayments
            ->filter(function (PpdbFormPayment $payment) use ($search) {
                if ($search === '') {
                    return true;
                }

                $haystack = collect([
                    $payment->display_form_number,
                    $payment->display_buyer_name,
                    $payment->display_student_name,
                    $payment->user?->email,
                ])->filter()->implode(' ');

                return str_contains(strtolower($haystack), strtolower($search));
            })
            ->when($status !== '', fn (Collection $items) => $items->where('display_payment_status_key', $status))
            ->when($academicYear !== '', fn (Collection $items) => $items->where('display_academic_year', $academicYear))
            ->values();

        $payments = $this->paginateCollection($filteredPayments, $request, 10);
        $paidPayments = $allPayments->where('display_payment_status_key', 'lunas');
        $pendingCount = $allPayments->where('display_payment_status_key', 'menunggu')->count();
        $completedForms = $allPayments->where('display_filling_status_label', 'Diisi Lengkap')->count();
        $completionRate = $allPayments->count() > 0
            ? round(($completedForms / $allPayments->count()) * 100, 1)
            : 0;

        return view('dashboard.panitia.finance.form-payments', [
            'payments' => $payments,
            'search' => $search,
            'status' => $status,
            'academicYear' => $academicYear,
            'statusOptions' => [
                'lunas' => 'Lunas',
                'menunggu' => 'Menunggu',
            ],
            'academicYearOptions' => $allPayments->pluck('display_academic_year')->filter()->unique()->sortDesc()->values(),
            'stats' => [
                'total_forms' => $allPayments->count(),
                'total_income' => $paidPayments->sum('amount'),
                'completed_forms' => $completedForms,
                'completion_rate' => $completionRate,
                'unconfirmed_count' => $pendingCount,
                'current_academic_year' => $academicYear !== '' ? $academicYear : ($allPayments->pluck('display_academic_year')->filter()->first() ?? $this->resolveAcademicYear(now())),
            ],
        ]);
    }

    public function exportFormPayments(Request $request): Response
    {
        Carbon::setLocale('id');

        $search = trim((string) $request->string('q'));
        $status = (string) $request->string('status');
        $academicYear = (string) $request->string('ta');

        $rows = PpdbFormPayment::query()
            ->with('user.studentRegistration')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (PpdbFormPayment $payment) => $this->decorateFormPayment($payment))
            ->filter(function (PpdbFormPayment $payment) use ($search) {
                if ($search === '') {
                    return true;
                }

                $haystack = collect([
                    $payment->display_form_number,
                    $payment->display_buyer_name,
                    $payment->display_student_name,
                    $payment->user?->email,
                ])->filter()->implode(' ');

                return str_contains(strtolower($haystack), strtolower($search));
            })
            ->when($status !== '', fn (Collection $items) => $items->where('display_payment_status_key', $status))
            ->when($academicYear !== '', fn (Collection $items) => $items->where('display_academic_year', $academicYear))
            ->values();

        $csv = collect([
            ['No. Formulir', 'Nama Pembeli', 'Nama Calon Siswa', 'Tanggal Bayar', 'Jumlah', 'Metode', 'Status', 'Status Pengisian', 'Tahun Ajaran'],
        ])->concat(
            $rows->map(fn (PpdbFormPayment $payment) => [
                $payment->display_form_number,
                $payment->display_buyer_name,
                $payment->display_student_name,
                $payment->display_payment_date,
                $payment->display_form_amount,
                $payment->display_form_method,
                $payment->display_payment_status_label,
                $payment->display_filling_status_label,
                $payment->display_academic_year,
            ])
        )->map(fn (array $columns) => implode(',', array_map(fn ($value) => '"' . str_replace('"', '""', (string) $value) . '"', $columns)))
            ->implode("\n");

        $fileName = 'keuangan-bayar-formulir-' . now()->format('Ymd-His') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function reRegistrations(Request $request): View
    {
        Carbon::setLocale('id');

        $search = trim((string) $request->string('q'));
        $paymentType = (string) $request->string('jenis');
        $status = (string) $request->string('status');
        $academicYear = (string) $request->string('ta');

        $allRecords = StudentRegistration::query()
            ->with('user')
            ->where('selection_result', 'lulus')
            ->orderByDesc('selection_published_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (StudentRegistration $registration) => $this->decorateReRegistrationPayment($registration));

        $filteredRecords = $allRecords
            ->filter(function (StudentRegistration $registration) use ($search) {
                if ($search === '') {
                    return true;
                }

                $haystack = collect([
                    $registration->display_form_number,
                    $registration->display_student_name,
                    $registration->display_buyer_name,
                    $registration->user?->email,
                ])->filter()->implode(' ');

                return str_contains(strtolower($haystack), strtolower($search));
            })
            ->when($paymentType !== '', fn (Collection $items) => $items->where('display_rereg_payment_type_key', $paymentType))
            ->when($status !== '', fn (Collection $items) => $items->where('display_rereg_status_key', $status))
            ->when($academicYear !== '', fn (Collection $items) => $items->where('display_academic_year', $academicYear))
            ->values();

        $records = $this->paginateCollection($filteredRecords, $request, 10);
        $paidCount = $allRecords->where('display_rereg_status_key', 'lunas')->count();
        $unpaidCount = $allRecords->where('display_rereg_status_key', 'belum_lunas')->count();
        $paidPercentage = $allRecords->count() > 0 ? round(($paidCount / $allRecords->count()) * 100, 1) : 0;
        $unpaidPercentage = $allRecords->count() > 0 ? round(($unpaidCount / $allRecords->count()) * 100, 1) : 0;

        return view('dashboard.panitia.finance.re-registrations', [
            'records' => $records,
            'search' => $search,
            'paymentType' => $paymentType,
            'status' => $status,
            'academicYear' => $academicYear,
            'paymentTypeOptions' => [
                'midtrans' => 'Midtrans',
                'manual_transfer' => 'Transfer BRI / DANA',
                'manual_cash' => 'Cash ke Sekolah',
            ],
            'statusOptions' => [
                'lunas' => 'Lunas',
                'belum_lunas' => 'Belum Lunas',
            ],
            'academicYearOptions' => $allRecords->pluck('display_academic_year')->filter()->unique()->sortDesc()->values(),
            'stats' => [
                'accepted_students' => $allRecords->count(),
                'paid_count' => $paidCount,
                'unpaid_count' => $unpaidCount,
                'paid_percentage' => $paidPercentage,
                'unpaid_percentage' => $unpaidPercentage,
                'current_academic_year' => $academicYear !== '' ? $academicYear : ($allRecords->pluck('display_academic_year')->filter()->first() ?? $this->resolveAcademicYear(now())),
            ],
        ]);
    }

    public function exportReRegistrations(Request $request): Response
    {
        Carbon::setLocale('id');

        $search = trim((string) $request->string('q'));
        $paymentType = (string) $request->string('jenis');
        $status = (string) $request->string('status');
        $academicYear = (string) $request->string('ta');

        $rows = StudentRegistration::query()
            ->with('user')
            ->where('selection_result', 'lulus')
            ->orderByDesc('selection_published_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (StudentRegistration $registration) => $this->decorateReRegistrationPayment($registration))
            ->filter(function (StudentRegistration $registration) use ($search) {
                if ($search === '') {
                    return true;
                }

                $haystack = collect([
                    $registration->display_form_number,
                    $registration->display_student_name,
                    $registration->display_buyer_name,
                    $registration->user?->email,
                ])->filter()->implode(' ');

                return str_contains(strtolower($haystack), strtolower($search));
            })
            ->when($paymentType !== '', fn (Collection $items) => $items->where('display_rereg_payment_type_key', $paymentType))
            ->when($status !== '', fn (Collection $items) => $items->where('display_rereg_status_key', $status))
            ->when($academicYear !== '', fn (Collection $items) => $items->where('display_academic_year', $academicYear))
            ->values();

        $csv = collect([
            ['No. Formulir', 'Nama Siswa', 'Kelas', 'Total Biaya', 'Jenis Pembayaran', 'Detail Cicilan', 'Status Pelunasan', 'Tahun Ajaran'],
        ])->concat(
            $rows->map(fn (StudentRegistration $registration) => [
                $registration->display_form_number,
                $registration->display_student_name,
                $registration->display_class,
                $registration->display_rereg_amount,
                $registration->display_rereg_payment_type_label,
                $registration->display_installment_export,
                $registration->display_rereg_status_label,
                $registration->display_academic_year,
            ])
        )->map(fn (array $columns) => implode(',', array_map(fn ($value) => '"' . str_replace('"', '""', (string) $value) . '"', $columns)))
            ->implode("\n");

        $fileName = 'keuangan-daftar-ulang-' . now()->format('Ymd-His') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function decorateFormPayment(PpdbFormPayment $payment): PpdbFormPayment
    {
        $registration = $payment->user?->studentRegistration;
        $purchaseDate = $payment->paid_at ?? $payment->created_at ?? now();
        $statusKey = $payment->isPaid() ? 'lunas' : 'menunggu';
        $statusTone = $statusKey === 'lunas' ? 'emerald' : 'amber';

        $payment->display_form_number = $payment->order_id;
        $payment->display_buyer_name = $payment->user?->name ?? '-';
        $payment->display_student_name = filled($registration?->full_name) ? $registration->full_name : '-';
        $payment->display_payment_date = $purchaseDate->translatedFormat('j M Y');
        $payment->display_form_amount = $this->formatCurrency($payment->amount ?: self::FORM_PAYMENT_AMOUNT);
        $payment->display_form_method = match ($payment->payment_type) {
            'manual_transfer' => 'Transfer BRI / DANA',
            'manual_cash' => 'Cash ke Sekolah',
            default => $payment->payment_type ? Str::headline(str_replace('_', ' ', $payment->payment_type)) : 'Midtrans',
        };
        $payment->display_payment_status_key = $statusKey;
        $payment->display_payment_status_label = $statusKey === 'lunas'
            ? 'Lunas'
            : ($payment->status === 'manual_pending' ? 'Menunggu Verifikasi' : 'Menunggu');
        $payment->display_payment_status_tone = $statusTone;
        $payment->display_filling_status_label = $registration?->submitted_at
            ? 'Diisi Lengkap'
            : 'Belum Lengkap';
        $payment->display_filling_status_tone = $registration?->submitted_at
            ? 'emerald'
            : 'amber';
        $payment->display_academic_year = $this->resolveAcademicYear($purchaseDate);

        return $payment;
    }

    private function decorateReRegistrationPayment(StudentRegistration $registration): StudentRegistration
    {
        $amount = $registration->reregistration_amount ?: (int) config('ppdb_notifications.amounts.re_registration', self::REREGISTRATION_AMOUNT);
        $paymentTypeKey = str_starts_with((string) $registration->reregistration_payment_type, 'manual_')
            ? $registration->reregistration_payment_type
            : 'midtrans';
        $statusKey = $registration->reregistration_paid_at && in_array($registration->reregistration_status, ['settlement', 'capture'], true)
            ? 'lunas'
            : 'belum_lunas';
        $installments = $this->buildInstallments($registration, $paymentTypeKey, $statusKey);
        $baseDate = $registration->selection_published_at ?? $registration->submitted_at ?? $registration->created_at ?? now();

        $registration->display_form_number = $this->resolveFormNumber($registration);
        $registration->display_student_name = filled($registration->full_name) ? $registration->full_name : '-';
        $registration->display_buyer_name = $registration->user?->name ?? '-';
        $registration->display_class = $this->resolveClassLabel($registration);
        $registration->display_rereg_amount = $this->formatCurrency($amount);
        $registration->display_rereg_payment_type_key = $paymentTypeKey;
        $registration->display_rereg_payment_type_label = match ($registration->reregistration_payment_type) {
            'manual_transfer' => 'Transfer BRI / DANA',
            'manual_cash' => 'Cash ke Sekolah',
            default => $registration->reregistration_payment_type ? Str::headline(str_replace('_', ' ', $registration->reregistration_payment_type)) : 'Midtrans',
        };
        $registration->display_rereg_payment_type_tone = 'blue';
        $registration->display_rereg_status_key = $statusKey;
        $registration->display_rereg_status_label = $statusKey === 'lunas'
            ? 'Lunas'
            : ($registration->reregistration_status === 'manual_pending' ? 'Menunggu Verifikasi' : 'Belum Lunas');
        $registration->display_rereg_status_tone = $statusKey === 'lunas' ? 'emerald' : 'amber';
        $registration->display_installments = $installments;
        $registration->display_installment_export = collect($installments)
            ->map(fn (array $item) => $item['label'] . ': ' . $item['text'])
            ->implode(' | ');
        $registration->display_academic_year = $this->resolveAcademicYear($baseDate);

        return $registration;
    }

    private function buildInstallments(StudentRegistration $registration, string $paymentTypeKey, string $statusKey): array
    {
        return [
            [
                'label' => $statusKey === 'lunas' ? 'Lunas' : 'Menunggu',
                'tone' => $statusKey === 'lunas' ? 'emerald' : 'amber',
                'text' => $statusKey === 'lunas'
                    ? 'Dibayar pada ' . optional($registration->reregistration_paid_at)->translatedFormat('j M Y')
                    : 'Belum ada pembayaran berhasil',
            ],
        ];
    }

    private function resolveFormNumber(StudentRegistration $registration): string
    {
        if (filled($registration->registration_number)) {
            return (string) $registration->registration_number;
        }

        $year = ($registration->created_at ?? now())->format('Y');

        return $year . '-' . str_pad((string) $registration->id, 4, '0', STR_PAD_LEFT);
    }

    private function resolveAcademicYear(Carbon $baseDate): string
    {
        $year = (int) $baseDate->format('Y');

        if ((int) $baseDate->format('n') < 7) {
            return ($year - 1) . '/' . $year;
        }

        return $year . '/' . ($year + 1);
    }

    private function resolveClassLabel(StudentRegistration $registration): string
    {
        if ($registration->birth_date && Carbon::parse($registration->birth_date)->diffInYears(now()->copy()->startOfYear()->addMonths(6)) < 5) {
            return 'A';
        }

        return match ($registration->interview_room) {
            'Ruang Wawancara A' => 'B1',
            'Ruang Wawancara B' => 'B2',
            'Ruang Wawancara C' => 'B3',
            default => match ($registration->id % 3) {
                1 => 'B1',
                2 => 'B2',
                default => 'B3',
            },
        };
    }

    private function formatCurrency(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    private function paginateCollection(Collection $items, Request $request, int $perPage): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $results = $items->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $results,
            $items->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }
}
