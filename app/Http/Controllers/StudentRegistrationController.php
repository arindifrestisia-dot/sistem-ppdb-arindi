<?php

namespace App\Http\Controllers;

use App\Models\StudentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentRegistrationController extends Controller
{
    public function edit(Request $request): View|RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        return view('dashboard.panel-ortu.data-diri-page', [
            'registration' => $request->user()->studentRegistration,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $redirect = $this->redirectPanitia($request);
        if ($redirect) {
            return $redirect;
        }

        $action = $request->input('action', 'submit');
        $registration = $request->user()->studentRegistration()->firstOrNew();

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

        $validated = $request->validate([
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
        ]);

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
        ]);

        $registration->user()->associate($request->user());

        $fileMap = [
            'child_photo' => 'child_photo_path',
            'parents_id_card' => 'parents_id_card_path',
            'birth_certificate' => 'birth_certificate_path',
            'family_card' => 'family_card_path',
        ];

        foreach ($fileMap as $inputName => $columnName) {
            if (! $request->hasFile($inputName)) {
                continue;
            }

            if ($registration->{$columnName}) {
                Storage::disk('public')->delete($registration->{$columnName});
            }

            $registration->{$columnName} = $request->file($inputName)->store('student-registrations', 'public');
        }

        $registration->save();

        if ($action !== 'submit') {
            return redirect()
                ->route('data-diri')
                ->with('status', 'Perubahan formulir berhasil disimpan.');
        }

        if (! $registration->registration_number) {
            $registration->registration_number = $this->generateRegistrationNumber();
        }

        $registration->submitted_at = now();
        $registration->locked_at ??= now();
        $registration->save();

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
        ]);

        $registration = $request->user()->studentRegistration;

        if (! $registration) {
            return redirect()->route('data-diri');
        }

        $registration->locked_at = now();
        $registration->save();

        return redirect()
            ->route('persyaratan')
            ->with('status', 'Pendaftaran berhasil dikunci. Silakan menunggu tahap selanjutnya.');
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
        ]);
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

        $days = [
            ['date' => '2026-04-13', 'room' => 'Ruang Wawancara A'],
            ['date' => '2026-04-14', 'room' => 'Ruang Wawancara B'],
            ['date' => '2026-04-15', 'room' => 'Ruang Wawancara C'],
        ];

        $sessions = [
            ['label' => 'Sesi 1', 'time' => '08.00 - 08.30 WIB'],
            ['label' => 'Sesi 2', 'time' => '09.00 - 09.30 WIB'],
            ['label' => 'Sesi 3', 'time' => '10.00 - 10.30 WIB'],
        ];

        $options = [];

        foreach ($days as $dayIndex => $day) {
            $date = Carbon::parse($day['date']);

            foreach ($sessions as $sessionIndex => $session) {
                $key = 'day-' . ($dayIndex + 1) . '-session-' . ($sessionIndex + 1);

                $options[$key] = [
                    'key' => $key,
                    'session_label' => $session['label'],
                    'date' => $date,
                    'day_name' => Str::headline($date->translatedFormat('l')),
                    'formatted_date' => $date->translatedFormat('d F Y'),
                    'time' => $session['time'],
                    'room' => $day['room'],
                ];
            }
        }

        return $options;
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

    protected function redirectPanitia(Request $request): ?RedirectResponse
    {
        return $request->user()?->isPanitia()
            ? redirect()->route('panitia.dashboard')
            : null;
    }
}
