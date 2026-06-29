<?php

namespace App\Http\Controllers;

use App\Models\ParentFormField;
use App\Models\StudentRegistration;
use App\Services\MidtransSnapService;
use App\Services\PpdbNotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;
use Illuminate\View\View;

class StudentRegistrationController extends Controller
{
    public function __construct(
        private readonly PpdbNotificationService $notifications,
        private readonly MidtransSnapService $midtrans,
    ) {
    }

    public function edit(Request $request): View|RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        if (! $request->user()->hasPaidPpdbForm()) {
            return redirect()
                ->route('ortu.formulir')
                ->with('status', 'Silakan lunasi pembelian formulir terlebih dahulu sebelum mengisi data diri.');
        }

        $customFields = ParentFormField::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('dashboard.panel-ortu.data-diri-page', [
            'registration' => $request->user()->studentRegistration,
            'childCustomFields' => $customFields->where('section', 'child')->values(),
            'parentCustomFields' => $customFields->where('section', 'parent')->values(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        if (! $request->user()->hasPaidPpdbForm()) {
            return redirect()
                ->route('ortu.formulir')
                ->with('status', 'Silakan lunasi pembelian formulir terlebih dahulu sebelum mengisi data diri.');
        }

        $action = $request->input('action', 'submit');
        $registration = $request->user()->studentRegistration()->firstOrNew();

        if ($registration->exists && $registration->locked_at) {
            return redirect()
                ->route('data-diri')
                ->with('status', 'Data pendaftaran telah dikunci permanen. Hubungi admin sekolah jika memerlukan perubahan.');
        }

        if ($action === 'lock' && ! $registration->submitted_at) {
            return redirect()
                ->route('data-diri')
                ->withErrors(['action' => 'Isi dan kirim formulir terlebih dahulu sebelum mengunci pendaftaran.']);
        }

        $fileRules = [
            'child_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'parents_id_card' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'birth_certificate' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'family_card' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ];

        if ($action === 'submit') {
            foreach ([
                'child_photo' => 'child_photo_path',
                'parents_id_card' => 'parents_id_card_path',
                'birth_certificate' => 'birth_certificate_path',
                'family_card' => 'family_card_path',
            ] as $inputName => $columnName) {
                if (! $registration->{$columnName}) {
                    array_unshift($fileRules[$inputName], 'required');
                }
            }
        }

        $customFields = ParentFormField::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $rules = [
            'action' => ['required', 'in:save,submit,lock'],
            'full_name' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'religion' => ['required', 'in:Islam,Kristen,Katolik,Hindu,Buddha'],
            'weight_kg' => ['required', 'numeric', 'min:0', 'max:999.99'],
            'height_cm' => ['required', 'numeric', 'min:0', 'max:999.99'],
            'home_address' => ['required', 'string'],
            'origin_region' => ['required', 'string', 'max:255'],
            'citizenship' => ['required', 'in:WNI,WNA'],
            'special_needs' => ['required', 'in:Ya,Tidak'],
            'special_needs_description' => ['required_if:special_needs,Ya', 'nullable', 'string'],
            'child_status' => ['required', 'in:Kandung,Tiri,Angkat'],
            'blood_type' => ['required', 'in:A,B,AB,O,Tidak Tahu'],
            'child_order' => ['required', 'integer', 'min:1'],
            'siblings_total' => ['required', 'integer', 'min:1'],
            'medical_history' => ['nullable', 'string'],
            'father_name' => ['required', 'string', 'max:255'],
            'father_birth_info' => ['required', 'string', 'max:255'],
            'father_religion' => ['required', 'in:Islam,Kristen,Katolik,Hindu,Buddha'],
            'father_citizenship' => ['required', 'in:WNI,WNA'],
            'father_status' => ['required', 'in:Kandung,Tiri,Angkat,Wali'],
            'father_job' => ['required', 'string', 'max:255'],
            'father_education' => ['required', 'string', 'max:255'],
            'father_income' => ['required', 'string', 'max:255'],
            'father_phone' => ['required', 'string', 'max:30'],
            'father_address' => ['required', 'string'],
            'mother_name' => ['required', 'string', 'max:255'],
            'mother_birth_info' => ['required', 'string', 'max:255'],
            'mother_religion' => ['required', 'in:Islam,Kristen,Katolik,Hindu,Buddha'],
            'mother_citizenship' => ['required', 'in:WNI,WNA'],
            'mother_status' => ['required', 'in:Kandung,Tiri,Angkat,Wali'],
            'mother_job' => ['required', 'string', 'max:255'],
            'mother_education' => ['required', 'string', 'max:255'],
            'mother_income' => ['required', 'string', 'max:255'],
            'mother_phone' => ['required', 'string', 'max:30'],
            'mother_address' => ['required', 'string'],
            'child_photo' => $fileRules['child_photo'],
            'parents_id_card' => $fileRules['parents_id_card'],
            'birth_certificate' => $fileRules['birth_certificate'],
            'family_card' => $fileRules['family_card'],
            'agreement' => [$action === 'submit' ? 'accepted' : 'nullable'],
        ];

        foreach ($customFields as $field) {
            $rules['custom_fields.' . $field->field_key] = $this->customFieldRules(
                $field,
                $action !== 'save'
            );
        }

        $validated = $request->validate($rules);

        $registration->fill([
            'full_name' => $validated['full_name'],
            'nickname' => $validated['nickname'],
            'gender' => $validated['gender'],
            'birth_place' => $validated['birth_place'],
            'birth_date' => $validated['birth_date'],
            'religion' => $validated['religion'],
            'weight_kg' => $validated['weight_kg'],
            'height_cm' => $validated['height_cm'],
            'home_address' => $validated['home_address'],
            'origin_region' => $validated['origin_region'],
            'citizenship' => $validated['citizenship'],
            'special_needs' => $validated['special_needs'] === 'Ya',
            'special_needs_description' => $validated['special_needs'] === 'Ya'
                ? ($validated['special_needs_description'] ?? null)
                : null,
            'child_status' => $validated['child_status'],
            'blood_type' => $validated['blood_type'],
            'child_order' => $validated['child_order'],
            'siblings_total' => $validated['siblings_total'],
            'medical_history' => $validated['medical_history'] ?? null,
            'father_name' => $validated['father_name'],
            'father_birth_info' => $validated['father_birth_info'],
            'father_religion' => $validated['father_religion'],
            'father_citizenship' => $validated['father_citizenship'],
            'father_status' => $validated['father_status'],
            'father_job' => $validated['father_job'],
            'father_education' => $validated['father_education'],
            'father_income' => $validated['father_income'],
            'father_phone' => $validated['father_phone'],
            'father_address' => $validated['father_address'],
            'mother_name' => $validated['mother_name'],
            'mother_birth_info' => $validated['mother_birth_info'],
            'mother_religion' => $validated['mother_religion'],
            'mother_citizenship' => $validated['mother_citizenship'],
            'mother_status' => $validated['mother_status'],
            'mother_job' => $validated['mother_job'],
            'mother_education' => $validated['mother_education'],
            'mother_income' => $validated['mother_income'],
            'mother_phone' => $validated['mother_phone'],
            'mother_address' => $validated['mother_address'],
            'custom_form_data' => array_merge(
                $registration->custom_form_data ?? [],
                $validated['custom_fields'] ?? []
            ),
        ]);

        $registration->user()->associate($request->user());

        $fileMap = [
            'child_photo' => 'child_photo_path',
            'parents_id_card' => 'parents_id_card_path',
            'birth_certificate' => 'birth_certificate_path',
            'family_card' => 'family_card_path',
        ];

        $hasUploadedDocuments = false;

        foreach ($fileMap as $inputName => $columnName) {
            if (! $request->hasFile($inputName)) {
                continue;
            }

            $hasUploadedDocuments = true;

            if ($registration->{$columnName}) {
                Storage::disk('public')->delete($registration->{$columnName});
            }

            $registration->{$columnName} = $request->file($inputName)->store('student-registrations', 'public');
        }

        $registration->save();

        if ($hasUploadedDocuments) {
            $this->notifications->send('documents_uploaded', $request->user(), $registration);
        }

        if ($action !== 'submit') {
            if ($action === 'lock') {
                $registration->locked_at = now();
                $registration->save();

                return redirect()
                    ->route('data-diri')
                    ->with('status', 'Data pendaftaran telah dikunci permanen.');
            }

            return redirect()
                ->route('data-diri')
                ->with('status', 'Perubahan formulir berhasil disimpan.');
        }

        if (! $registration->registration_number) {
            $registration->registration_number = $this->generateRegistrationNumber();
        }

        $registration->submitted_at = now();
        $registration->save();

        $this->notifications->send('form_payment_instruction', $request->user(), $registration);
        $this->notifications->send('form_submitted', $request->user(), $registration);
        $this->notifications->send('registration_processing', $request->user(), $registration);

        return redirect()
            ->route('data-diri.success')
            ->with('status', 'Formulir pendaftaran berhasil disimpan.');
    }

    public function success(Request $request): View|RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        $registration = $request->user()->studentRegistration;

        if (! $registration || ! $registration->submitted_at) {
            return redirect()->route('data-diri');
        }

        return view('dashboard.panel-ortu.data-diri-success', [
            'registration' => $registration,
        ]);
    }

    public function requirements(Request $request): View|RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        $registration = $request->user()->studentRegistration;

        if (! $registration) {
            return redirect()->route('data-diri');
        }

        return view('dashboard.panel-ortu.persyaratan', [
            'registration' => $registration,
        ]);
    }

    public function lock(Request $request): RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        $request->validate([
            'review_agreement' => ['accepted'],
            'redirect_to' => ['nullable', 'in:data-diri,persyaratan'],
        ]);

        $registration = $request->user()->studentRegistration;

        if (! $registration || ! $registration->submitted_at) {
            return redirect()->route('data-diri');
        }

        if (! $registration->locked_at) {
            $registration->locked_at = now();
            $registration->save();
        }

        return redirect()
            ->route($request->input('redirect_to', 'persyaratan'))
            ->with('status', 'Data pendaftaran telah dikunci permanen.');
    }

    public function interview(Request $request): View|RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        $registration = $request->user()->studentRegistration;
        $isInterviewAvailable = $this->canAccessInterviewSchedule($registration);

        return view('dashboard.panel-ortu.wawancara', [
            'registration' => $registration,
            'scheduleOptions' => $isInterviewAvailable ? $this->getInterviewScheduleOptions() : [],
            'scheduleCalendar' => $isInterviewAvailable ? $this->getInterviewScheduleCalendar() : [
                'months' => [],
                'slotsByDate' => [],
            ],
            'isInterviewAvailable' => $isInterviewAvailable,
        ]);
    }

    public function graduationStatus(Request $request): View|RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        $registration = $request->user()->studentRegistration;
        $isSelectionPublished = (bool) ($registration && $registration->selection_published_at);

        return view('dashboard.panel-ortu.status-lulus', [
            'registration' => $registration,
            'isSelectionPublished' => $isSelectionPublished,
            'selectionResultLabel' => $this->getSelectionResultLabel($registration?->selection_result),
            'selectionResultTone' => $this->getSelectionResultTone($registration?->selection_result),
            'canPayReRegistration' => $this->canPayReRegistration($registration),
        ]);
    }

    public function reRegistration(Request $request): View|RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        $registration = $request->user()->studentRegistration;

        if (! $this->canPayReRegistration($registration)) {
            return redirect()
                ->route('status-lulus')
                ->with('status', 'Pendaftaran ulang hanya tersedia untuk calon siswa yang dinyatakan lulus.');
        }

        return view('dashboard.panel-ortu.daftar-ulang', [
            'registration' => $registration,
            'reRegistrationAmountLabel' => $this->formatCurrency((int) config('ppdb_notifications.amounts.re_registration', 1550000)),
            'reRegistrationDeadline' => $registration?->selection_published_at
                ? $registration->selection_published_at->copy()->addDays((int) config('ppdb_notifications.deadlines.re_registration_days', 7))
                : null,
            'reRegistrationPaymentMethodLabel' => $this->getReRegistrationPaymentMethodLabel($registration),
            'midtransClientKey' => (string) config('services.midtrans.client_key'),
            'isMidtransConfigured' => $this->midtrans->isConfigured(),
            'isReRegistrationPaid' => $this->isReRegistrationPaid($registration),
            'reRegistrationPaymentPlans' => $this->getReRegistrationPaymentPlans(),
            'selectedReRegistrationPlan' => $this->getSelectedReRegistrationPlan($registration),
            'reRegistrationInstallmentStatus' => $this->getReRegistrationInstallmentStatus($registration),
        ]);
    }

    public function createReRegistrationPayment(Request $request): JsonResponse
    {
        if ($request->user()?->isPanitia()) {
            return response()->json(['message' => 'Akses panitia tidak dapat membuat pembayaran daftar ulang.'], 403);
        }

        $registration = $request->user()->studentRegistration;

        if (! $this->canPayReRegistration($registration)) {
            return response()->json(['message' => 'Pembayaran daftar ulang hanya tersedia untuk calon siswa yang dinyatakan lulus.'], 403);
        }

        if ($this->isReRegistrationPaid($registration)) {
            return response()->json(['status' => 'paid', 'message' => 'Pembayaran daftar ulang sudah lunas.']);
        }

        if ($registration->reregistration_status === 'manual_pending') {
            return response()->json(['message' => 'Pembayaran sedang menunggu verifikasi panitia.'], 422);
        }

        if (! $this->midtrans->isConfigured()) {
            return response()->json(['message' => 'Konfigurasi Midtrans sandbox belum lengkap.'], 422);
        }

        $validated = $request->validate([
            'payment_plan' => ['required', Rule::in(array_keys($this->getReRegistrationPaymentPlans()))],
        ]);

        $this->saveReRegistrationPlan($registration, $validated['payment_plan']);

        $amount = $this->getCurrentReRegistrationPaymentAmount($registration);

        if (! $registration->reregistration_order_id || in_array($registration->reregistration_status, ['deny', 'cancel', 'expire', 'failure', 'installment_partial'], true)) {
            $registration->forceFill([
                'reregistration_order_id' => $this->generateReRegistrationOrderId($registration),
                'reregistration_snap_token' => null,
                'reregistration_snap_redirect_url' => null,
                'reregistration_amount' => $amount,
                'reregistration_status' => 'pending',
            ])->save();
        }

        if ($registration->reregistration_snap_token && $registration->reregistration_status === 'pending') {
            return response()->json([
                'status' => 'pending',
                'snap_token' => $registration->reregistration_snap_token,
                'order_id' => $registration->reregistration_order_id,
                'payment_method_label' => $this->getReRegistrationPaymentMethodLabel($registration),
                'payment_plan_label' => $this->getSelectedReRegistrationPlanDefinition($registration)['name'],
                'installment_status' => $this->getReRegistrationInstallmentStatus($registration),
            ]);
        }

        try {
            $transaction = $this->midtrans->createReRegistrationTransaction($registration, $request->user(), $amount);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        $registration->forceFill([
            'reregistration_snap_token' => (string) ($transaction['token'] ?? ''),
            'reregistration_snap_redirect_url' => $transaction['redirect_url'] ?? null,
            'reregistration_status' => 'pending',
            'reregistration_payment_type' => 'midtrans',
            'reregistration_midtrans_payload' => $transaction,
        ])->save();

        return response()->json([
            'status' => 'pending',
            'snap_token' => $registration->reregistration_snap_token,
            'redirect_url' => $registration->reregistration_snap_redirect_url,
            'order_id' => $registration->reregistration_order_id,
            'payment_method_label' => $this->getReRegistrationPaymentMethodLabel($registration),
            'payment_plan_label' => $this->getSelectedReRegistrationPlanDefinition($registration)['name'],
            'installment_status' => $this->getReRegistrationInstallmentStatus($registration),
        ]);
    }

    public function syncReRegistrationPayment(Request $request): JsonResponse
    {
        $registration = $request->user()->studentRegistration;

        if (! $this->canPayReRegistration($registration) || ! $registration->reregistration_order_id) {
            return response()->json(['message' => 'Transaksi daftar ulang tidak ditemukan.'], 404);
        }

        try {
            $payload = $this->midtrans->getTransactionStatus($registration->reregistration_order_id);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        $this->applyMidtransReRegistrationStatus($registration, $payload);
        $registration->refresh();

        return response()->json([
            'status' => $registration->reregistration_status,
            'paid' => $this->isReRegistrationPaid($registration),
            'order_id' => $registration->reregistration_order_id,
            'payment_method_label' => $this->getReRegistrationPaymentMethodLabel($registration),
            'payment_plan_label' => $this->getSelectedReRegistrationPlanDefinition($registration)['name'],
            'installment_status' => $this->getReRegistrationInstallmentStatus($registration),
        ]);
    }

    public function submitManualReRegistration(Request $request): RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        $registration = $request->user()->studentRegistration;
        abort_unless($this->canPayReRegistration($registration), 403);

        if ($this->isReRegistrationPaid($registration)) {
            return back()->with('status', 'Pembayaran daftar ulang sudah lunas.');
        }

        if ($registration->reregistration_status === 'manual_pending') {
            return back()->with('status', 'Bukti pembayaran sudah dikirim dan sedang menunggu verifikasi panitia.');
        }

        $validated = $request->validate([
            'payment_plan' => ['required', Rule::in(array_keys($this->getReRegistrationPaymentPlans()))],
            'payment_method' => ['required', 'in:transfer,cash'],
            'proof' => ['nullable', 'required_if:payment_method,transfer', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'proof.required_if' => 'Bukti pembayaran wajib diunggah untuk metode transfer atau DANA.',
            'proof.mimes' => 'Bukti pembayaran harus berupa JPG, PNG, atau PDF.',
            'proof.max' => 'Ukuran bukti pembayaran maksimal 5 MB.',
        ]);

        $this->saveReRegistrationPlan($registration, $validated['payment_plan']);

        $oldProof = $registration->reregistration_proof_path;
        $proofPath = $request->hasFile('proof')
            ? $request->file('proof')->store('payment-proofs/reregistration', 'public')
            : $oldProof;

        $registration->forceFill([
            'reregistration_order_id' => $registration->reregistration_order_id ?: $this->generateReRegistrationOrderId($registration),
            'reregistration_amount' => $this->getCurrentReRegistrationPaymentAmount($registration),
            'reregistration_status' => 'manual_pending',
            'reregistration_payment_type' => 'manual_' . $validated['payment_method'],
            'reregistration_proof_path' => $proofPath,
            'reregistration_paid_at' => null,
            'reregistration_verified_by' => null,
            'reregistration_verified_at' => null,
        ])->save();

        $verifiedProofPaths = collect(data_get($registration->custom_form_data ?? [], 'reregistration_paid_installments', []))
            ->pluck('proof_path')
            ->filter()
            ->all();

        if ($request->hasFile('proof') && $oldProof && $oldProof !== $proofPath && ! in_array($oldProof, $verifiedProofPaths, true)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldProof);
        }

        if ($request->hasFile('proof')) {
            $this->notifications->send('re_registration_proof_uploaded', $request->user(), $registration, [
                'payment_amount' => $this->formatCurrency((int) $registration->reregistration_amount),
                'payment_method' => $this->getReRegistrationPaymentMethodLabel($registration),
                'deduplication_suffix' => 'termin-' . $this->getCurrentReRegistrationInstallment($registration) . ':' . now()->format('YmdHis'),
            ]);
        }

        return back()->with('status', 'Pembayaran daftar ulang dikirim dan sedang menunggu verifikasi panitia.');
    }

    public function handleMidtransReRegistrationNotification(Request $request): JsonResponse
    {
        $payload = $request->all();

        if (! $this->midtrans->verifySignature($payload)) {
            return response()->json(['message' => 'Signature Midtrans tidak valid.'], 403);
        }

        $registration = StudentRegistration::where('reregistration_order_id', $payload['order_id'] ?? null)->first();

        if (! $registration) {
            return response()->json(['message' => 'Transaksi daftar ulang tidak ditemukan.'], 404);
        }

        $this->applyMidtransReRegistrationStatus($registration, $payload);

        return response()->json(['message' => 'OK']);
    }

    public function storeInterview(Request $request): RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        $registration = $request->user()->studentRegistration;

        if (! $this->canAccessInterviewSchedule($registration)) {
            return redirect()
                ->route('wawancara')
                ->withErrors([
                    'interview_schedule_key' => 'Tidak ada Jadwal Wawancara yang tersedia, silahkan lengkapi formulir pendaftaran anda',
                ]);
        }

        if ($registration?->interview_selected_at) {
            return redirect()
                ->route('wawancara')
                ->withErrors([
                    'interview_schedule_key' => 'Jadwal wawancara sudah dipilih dan tidak dapat diubah lagi.',
                ]);
        }

        $scheduleOptions = $this->getInterviewScheduleOptions();

        $validated = $request->validate([
            'interview_schedule_key' => ['required', 'string', 'in:' . implode(',', array_keys($scheduleOptions))],
        ]);

        $selectedSchedule = $scheduleOptions[$validated['interview_schedule_key']];

        $registration->fill([
            'interview_schedule_key' => $validated['interview_schedule_key'],
            'interview_date' => $selectedSchedule['date']->toDateString(),
            'interview_day_name' => $selectedSchedule['day_name'],
            'interview_time' => $selectedSchedule['time'],
            'interview_room' => $selectedSchedule['room'],
            'interview_selected_at' => now(),
        ]);

        $registration->save();

        $this->notifications->send('interview_schedule_selected', $request->user(), $registration);

        return redirect()
            ->route('wawancara')
            ->with('status', 'Jadwal wawancara berhasil dipilih.');
    }

    public function downloadFormPdf(Request $request): Response|RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        $registration = $request->user()->studentRegistration;

        if (! $registration || ! $registration->submitted_at) {
            return redirect()->route('data-diri');
        }

        $pdf = Pdf::loadView('dashboard.panel-ortu.pdf.formulir-pendaftaran', [
            'registration' => $registration,
            'user' => $request->user(),
        ])->setPaper('a4');

        return $pdf->download('formulir-pendaftaran-' . Str::slug($registration->full_name) . '.pdf');
    }

    public function downloadCardPdf(Request $request): Response|RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        $registration = $request->user()->studentRegistration;

        if (! $registration || ! $registration->submitted_at) {
            return redirect()->route('data-diri');
        }

        $pdf = Pdf::loadView('dashboard.panel-ortu.pdf.kartu-bukti', [
            'registration' => $registration,
            'user' => $request->user(),
        ])->setPaper('a4');

        return $pdf->download('kartu-bukti-' . Str::slug($registration->registration_number) . '.pdf');
    }

    protected function generateRegistrationNumber(): string
    {
        do {
            $number = 'PPDB-' . now()->format('Y') . '-' . str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (StudentRegistration::where('registration_number', $number)->exists());

        return $number;
    }

    protected function getInterviewScheduleOptions(): array
    {
        Carbon::setLocale('id');

        $options = [];
        $period = $this->interviewSchedulePeriod();
        $date = $period['start']->copy();

        while ($date->lte($period['end'])) {
            $key = $date->toDateString();
            $options[$key] = [
                'key' => $key,
                'session_label' => 'Jadwal Wawancara',
                'date' => $date->copy(),
                'day_name' => Str::headline($date->translatedFormat('l')),
                'formatted_date' => $date->translatedFormat('d F Y'),
                'time' => 'Silahkan datang ke sekolah RA FADHILAH pada jam 08.00 - 13.00',
                'room' => 'RUANGAN TU',
            ];

            $date->addDay();
        }

        return $options;
    }

    protected function getInterviewScheduleCalendar(): array
    {
        $options = collect($this->getInterviewScheduleOptions());
        $slotsByDate = $options
            ->groupBy(fn (array $option) => $option['date']->toDateString())
            ->map(fn ($items) => $items->map(fn (array $option) => [
                'key' => $option['key'],
                'session_label' => $option['session_label'],
                'date' => $option['date']->toDateString(),
                'formatted_date' => $option['formatted_date'],
                'day_name' => $option['day_name'],
                'time' => $option['time'],
                'room' => $option['room'],
            ])->values()->all())
            ->all();

        $period = $this->interviewSchedulePeriod();
        $months = [];
        $month = $period['start']->copy()->startOfMonth();

        while ($month->lte($period['end'])) {
            $months[] = [
                'key' => $month->format('Y-m'),
                'label' => $month->translatedFormat('F Y'),
                'year' => (int) $month->format('Y'),
                'month' => (int) $month->format('n'),
            ];

            $month->addMonth();
        }

        return [
            'months' => $months,
            'slotsByDate' => $slotsByDate,
        ];
    }

    protected function interviewSchedulePeriod(): array
    {
        return [
            'start' => Carbon::create(2026, 1, 1)->startOfDay(),
            'end' => Carbon::create(2026, 12, 31)->endOfDay(),
        ];
    }

    protected function interviewRoomForDate(Carbon $date): string
    {
        return match ((int) $date->format('N') % 3) {
            1 => 'Ruang Wawancara A',
            2 => 'Ruang Wawancara B',
            default => 'Ruang Wawancara C',
        };
    }

    protected function canAccessInterviewSchedule(?StudentRegistration $registration): bool
    {
        return (bool) ($registration && $registration->submitted_at);
    }

    protected function getSelectionResultLabel(?string $selectionResult): string
    {
        return match ($selectionResult) {
            'lulus' => 'Lulus',
            'tidak_lulus' => 'Tidak Lulus',
            default => 'Menunggu Hasil',
        };
    }

    protected function getSelectionResultTone(?string $selectionResult): string
    {
        return match ($selectionResult) {
            'lulus' => 'emerald',
            'tidak_lulus' => 'rose',
            default => 'slate',
        };
    }

    protected function getReRegistrationPaymentMethodLabel(?StudentRegistration $registration): string
    {
        return match ($registration?->reregistration_payment_type) {
            'manual_transfer' => 'Transfer BRI / DANA',
            'manual_cash' => 'Cash ke Sekolah',
            default => 'Midtrans Sandbox',
        };
    }

    protected function canPayReRegistration(?StudentRegistration $registration): bool
    {
        return (bool) (
            $registration
            && $registration->selection_result === 'lulus'
            && $registration->selection_published_at
        );
    }

    protected function isReRegistrationPaid(?StudentRegistration $registration): bool
    {
        if (! $registration) {
            return false;
        }

        $installmentStatus = $this->getReRegistrationInstallmentStatus($registration);

        if ($installmentStatus['total_installments'] > 1) {
            return $installmentStatus['paid_installments'] >= $installmentStatus['total_installments']
                && in_array($registration->reregistration_status, ['settlement', 'capture'], true)
                && $registration->reregistration_paid_at;
        }

        return (bool) (
            in_array($registration->reregistration_status, ['settlement', 'capture'], true)
            && $registration->reregistration_paid_at
        );
    }

    protected function applyMidtransReRegistrationStatus(StudentRegistration $registration, array $payload): void
    {
        $wasPaid = $this->isReRegistrationPaid($registration);
        $transactionStatus = (string) ($payload['transaction_status'] ?? $registration->reregistration_status ?? 'pending');
        $fraudStatus = $payload['fraud_status'] ?? null;
        $isPaid = $transactionStatus === 'settlement'
            || ($transactionStatus === 'capture' && in_array($fraudStatus, [null, 'accept'], true));

        $registration->forceFill([
            'reregistration_status' => $transactionStatus,
            'reregistration_payment_type' => $payload['payment_type'] ?? $registration->reregistration_payment_type,
            'reregistration_midtrans_payload' => $payload,
        ]);

        if ($isPaid) {
            $paidAt = $this->parseMidtransDate($payload['settlement_time'] ?? $payload['transaction_time'] ?? null);
            $this->recordReRegistrationInstallmentPayment(
                $registration,
                $registration->reregistration_order_id,
                (int) $registration->reregistration_amount,
                $payload['payment_type'] ?? 'midtrans',
                $paidAt
            );

            $installmentStatus = $this->getReRegistrationInstallmentStatus($registration);

            if ($installmentStatus['is_fully_paid']) {
                $registration->reregistration_paid_at = $registration->reregistration_paid_at ?: $paidAt;
                $registration->reregistration_status = $transactionStatus;
            } else {
                $registration->reregistration_paid_at = null;
                $registration->reregistration_status = 'installment_partial';
                $registration->reregistration_snap_token = null;
                $registration->reregistration_snap_redirect_url = null;
            }
        }

        $registration->save();

        if ($isPaid && ! $wasPaid && $registration->user) {
            $this->notifications->send('re_registration_approved', $registration->user, $registration);
            $this->notifications->send('student_officially_registered', $registration->user, $registration);
        }
    }

    protected function getReRegistrationPaymentPlans(): array
    {
        $total = (int) config('ppdb_notifications.amounts.re_registration', 1550000);

        return [
            'full' => [
                'name' => 'Lunas',
                'installments' => 1,
                'amounts' => [$total],
            ],
            'installment_2' => [
                'name' => 'Cicilan 2x',
                'installments' => 2,
                'amounts' => [775000, 775000],
            ],
            'installment_3' => [
                'name' => 'Cicilan 3x',
                'installments' => 3,
                'amounts' => [518000, 518000, 514000],
            ],
        ];
    }

    protected function getSelectedReRegistrationPlan(?StudentRegistration $registration): string
    {
        $plan = data_get($registration?->custom_form_data, 'reregistration_plan');

        return array_key_exists($plan, $this->getReRegistrationPaymentPlans()) ? $plan : 'full';
    }

    protected function getSelectedReRegistrationPlanDefinition(?StudentRegistration $registration): array
    {
        $plans = $this->getReRegistrationPaymentPlans();

        return $plans[$this->getSelectedReRegistrationPlan($registration)];
    }

    protected function saveReRegistrationPlan(StudentRegistration $registration, string $plan): void
    {
        $currentData = $registration->custom_form_data ?? [];
        $currentPlan = data_get($currentData, 'reregistration_plan');
        $hasPaidInstallments = count(data_get($currentData, 'reregistration_paid_installments', [])) > 0;

        if ($hasPaidInstallments && $currentPlan && $currentPlan !== $plan) {
            return;
        }

        if ($currentPlan === $plan) {
            return;
        }

        $currentData['reregistration_plan'] = $plan;
        $currentData['reregistration_paid_installments'] = [];
        $currentData['reregistration_current_installment'] = 1;

        $registration->custom_form_data = $currentData;
        $registration->reregistration_order_id = null;
        $registration->reregistration_snap_token = null;
        $registration->reregistration_snap_redirect_url = null;
        $registration->reregistration_status = null;
        $registration->reregistration_amount = null;
        $registration->save();
    }

    protected function getCurrentReRegistrationInstallment(StudentRegistration $registration): int
    {
        $paidCount = count(data_get($registration->custom_form_data ?? [], 'reregistration_paid_installments', []));
        $plan = $this->getSelectedReRegistrationPlanDefinition($registration);

        return min($paidCount + 1, $plan['installments']);
    }

    protected function getCurrentReRegistrationPaymentAmount(StudentRegistration $registration): int
    {
        $plan = $this->getSelectedReRegistrationPlanDefinition($registration);
        $installmentIndex = $this->getCurrentReRegistrationInstallment($registration) - 1;

        return (int) ($plan['amounts'][$installmentIndex] ?? $plan['amounts'][0]);
    }

    protected function getReRegistrationInstallmentStatus(?StudentRegistration $registration): array
    {
        $plan = $this->getSelectedReRegistrationPlanDefinition($registration);
        $paidInstallments = data_get($registration?->custom_form_data ?? [], 'reregistration_paid_installments', []);
        $paidInstallments = is_array($paidInstallments) ? $paidInstallments : [];

        if (
            $registration
            && $paidInstallments === []
            && $registration->reregistration_paid_at
            && in_array($registration->reregistration_status, ['settlement', 'capture'], true)
        ) {
            $paidInstallments[] = [
                'installment' => 1,
                'amount' => $registration->reregistration_amount ?: (int) config('ppdb_notifications.amounts.re_registration', 1550000),
                'method' => $registration->reregistration_payment_type ?: 'midtrans',
                'order_id' => $registration->reregistration_order_id,
                'proof_path' => $registration->reregistration_proof_path,
                'paid_at' => $registration->reregistration_paid_at->toDateTimeString(),
            ];
        }

        $paidCount = count($paidInstallments);

        return [
            'plan' => $this->getSelectedReRegistrationPlan($registration),
            'plan_label' => $plan['name'],
            'total_installments' => $plan['installments'],
            'paid_installments' => $paidCount,
            'current_installment' => min($paidCount + 1, $plan['installments']),
            'is_fully_paid' => $paidCount >= $plan['installments'],
            'paid_items' => $paidInstallments,
            'amounts' => $plan['amounts'],
        ];
    }

    protected function recordReRegistrationInstallmentPayment(
        StudentRegistration $registration,
        ?string $orderId,
        int $amount,
        string $method,
        Carbon $paidAt
    ): void {
        $customData = $registration->custom_form_data ?? [];
        $paidInstallments = data_get($customData, 'reregistration_paid_installments', []);
        $paidInstallments = is_array($paidInstallments) ? $paidInstallments : [];

        if ($orderId && collect($paidInstallments)->contains(fn (array $item) => ($item['order_id'] ?? null) === $orderId)) {
            return;
        }

        $paidInstallments[] = [
            'installment' => count($paidInstallments) + 1,
            'amount' => $amount,
            'method' => $method,
            'order_id' => $orderId,
            'proof_path' => $registration->reregistration_proof_path,
            'paid_at' => $paidAt->toDateTimeString(),
        ];

        $customData['reregistration_paid_installments'] = $paidInstallments;
        $customData['reregistration_current_installment'] = count($paidInstallments) + 1;
        $registration->custom_form_data = $customData;
    }

    protected function generateReRegistrationOrderId(StudentRegistration $registration): string
    {
        do {
            $orderId = 'DU-' . now()->format('Ymd') . '-' . $registration->id . '-' . Str::upper(Str::random(6));
        } while (StudentRegistration::where('reregistration_order_id', $orderId)->exists());

        return $orderId;
    }

    protected function parseMidtransDate(?string $date): Carbon
    {
        if (! $date) {
            return now();
        }

        return Carbon::parse($date);
    }

    protected function formatCurrency(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    protected function customFieldRules(ParentFormField $field, bool $enforceRequired): array
    {
        $rules = [$enforceRequired && $field->is_required ? 'required' : 'nullable'];

        return match ($field->type) {
            'number' => [...$rules, 'numeric'],
            'date' => [...$rules, 'date'],
            'select' => [...$rules, Rule::in($field->options ?? [])],
            'textarea' => [...$rules, 'string', 'max:5000'],
            default => [...$rules, 'string', 'max:255'],
        };
    }

    protected function redirectPanitia(Request $request): ?RedirectResponse
    {
        return $request->user()?->isPanitia()
            ? redirect()->route('panitia.dashboard')
            : null;
    }
}
