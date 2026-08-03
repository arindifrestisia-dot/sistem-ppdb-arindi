<?php

namespace App\Http\Controllers;

use App\Models\StudentRegistration;
use App\Models\InterviewSchedule;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PanitiaInterviewScheduleController extends Controller
{
    private const SCHEDULE_START_DATE = '2025-10-01';
    private const SCHEDULE_END_DATE = '2026-07-31';

    public function index(Request $request): View
    {
        Carbon::setLocale('id');

        $search = trim((string) $request->string('q'));
        $status = (string) $request->string('status');
        $academicYear = (string) $request->string('ta');

        $allRegistrations = StudentRegistration::query()
            ->with('user')
            ->whereNotNull('submitted_at')
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (StudentRegistration $registration) => $this->decorateRegistration($registration));

        $filteredRegistrations = $allRegistrations
            ->filter(function (StudentRegistration $registration) use ($search) {
                if ($search === '') {
                    return true;
                }

                $haystack = collect([
                    $registration->full_name,
                    $registration->registration_number,
                    $registration->user?->name,
                    $registration->user?->email,
                ])->filter()->implode(' ');

                return str_contains(strtolower($haystack), strtolower($search));
            })
            ->when($status !== '', fn (Collection $items) => $items->where('display_interview_status', $status))
            ->when($academicYear !== '', fn (Collection $items) => $items->where('display_academic_year', $academicYear))
            ->values();

        $registrations = $this->paginateCollection($filteredRegistrations, $request, 10);

        return view('dashboard.panitia.interviews.index', [
            'registrations' => $registrations,
            'search' => $search,
            'status' => $status,
            'academicYear' => $academicYear,
            'academicYearOptions' => $allRegistrations->pluck('display_academic_year')->filter()->unique()->sortDesc()->values(),
            'scheduleOptions' => $this->getInterviewScheduleOptions(),
            'managedSchedules' => Schema::hasTable('interview_schedules')
                ? InterviewSchedule::query()
                    ->whereBetween('interview_date', [self::SCHEDULE_START_DATE, self::SCHEDULE_END_DATE])
                    ->orderBy('interview_date')
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get()
                    ->reject(fn (InterviewSchedule $schedule) => $schedule->interview_date->isSunday())
                    ->values()
                : collect(),
            'statusOptions' => [
                'belum_pilih' => 'Belum Memilih',
                'sudah_pilih' => 'Sudah Memilih',
            ],
            'stats' => [
                'total' => $allRegistrations->count(),
                'selected' => $allRegistrations->where('display_interview_status', 'sudah_pilih')->count(),
                'waiting' => $allRegistrations->where('display_interview_status', 'belum_pilih')->count(),
                'completed' => $allRegistrations->where('display_interview_completion_status', 'selesai')->count(),
            ],
        ]);
    }

    public function storeSchedule(Request $request): RedirectResponse
    {
        $validated = $this->validateSchedule($request);
        $date = Carbon::parse($validated['interview_date']);

        if (! $this->isAllowedScheduleDate($date)) {
            return $this->scheduleDateError($request);
        }

        InterviewSchedule::create([
            'schedule_key' => $this->generateScheduleKey($date, $validated['interview_time'], $validated['room']),
            'interview_date' => $date->toDateString(),
            'session_label' => $validated['session_label'],
            'interview_time' => $validated['interview_time'],
            'room' => $validated['room'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('panitia.interviews.index', $request->only(['q', 'status', 'ta', 'page']))
            ->with('status', 'Jadwal wawancara berhasil ditambahkan.');
    }

    public function bulkStoreSchedules(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'days' => ['nullable', 'array'],
            'days.*' => ['integer', 'between:1,6'],
            'session_label' => ['required', 'string', 'max:100'],
            'interview_time' => ['required', 'string', 'max:100'],
            'room' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end = Carbon::parse($validated['end_date'])->startOfDay();

        if ($start->lt($this->scheduleStartDate()) || $end->gt($this->scheduleEndDate())) {
            return $this->scheduleDateError($request);
        }

        if ($start->diffInDays($end) > 366) {
            return redirect()
                ->route('panitia.interviews.index', $request->only(['q', 'status', 'ta', 'page']))
                ->withErrors(['schedule' => 'Rentang jadwal maksimal 366 hari untuk sekali tambah massal.']);
        }

        $selectedDays = collect($validated['days'] ?? [1, 2, 3, 4, 5, 6])
            ->map(fn ($day) => (int) $day)
            ->filter(fn (int $day) => $day >= 1 && $day <= 6)
            ->all();
        $created = 0;
        $skipped = 0;
        $date = $start->copy();

        while ($date->lte($end)) {
            if (! in_array((int) $date->format('N'), $selectedDays, true)) {
                $date->addDay();
                continue;
            }

            $exists = InterviewSchedule::query()
                ->whereDate('interview_date', $date->toDateString())
                ->where('interview_time', $validated['interview_time'])
                ->where('room', $validated['room'])
                ->exists();

            if ($exists) {
                $skipped++;
                $date->addDay();
                continue;
            }

            InterviewSchedule::create([
                'schedule_key' => $this->generateScheduleKey($date, $validated['interview_time'], $validated['room']),
                'interview_date' => $date->toDateString(),
                'session_label' => $validated['session_label'],
                'interview_time' => $validated['interview_time'],
                'room' => $validated['room'],
                'sort_order' => (int) ($validated['sort_order'] ?? 0),
                'is_active' => $request->boolean('is_active', true),
            ]);

            $created++;
            $date->addDay();
        }

        return redirect()
            ->route('panitia.interviews.index', $request->only(['q', 'status', 'ta', 'page']))
            ->with('status', "Tambah massal selesai. Dibuat: {$created} jadwal, dilewati: {$skipped} jadwal yang sudah ada.");
    }

    public function updateSchedule(Request $request, InterviewSchedule $schedule): RedirectResponse
    {
        $validated = $this->validateSchedule($request);
        $date = Carbon::parse($validated['interview_date']);

        if (! $this->isAllowedScheduleDate($date)) {
            return $this->scheduleDateError($request);
        }

        $schedule->update([
            'interview_date' => $date->toDateString(),
            'session_label' => $validated['session_label'],
            'interview_time' => $validated['interview_time'],
            'room' => $validated['room'],
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('panitia.interviews.index', $request->only(['q', 'status', 'ta', 'page']))
            ->with('status', 'Jadwal wawancara berhasil diperbarui.');
    }

    public function destroySchedule(Request $request, InterviewSchedule $schedule): RedirectResponse
    {
        $isUsed = StudentRegistration::query()
            ->where('interview_schedule_key', $schedule->schedule_key)
            ->exists();

        if ($isUsed) {
            return redirect()
                ->route('panitia.interviews.index', $request->only(['q', 'status', 'ta', 'page']))
                ->withErrors(['schedule' => 'Jadwal sudah dipilih pendaftar, jadi tidak dapat dihapus. Nonaktifkan jadwal jika tidak ingin ditampilkan lagi.']);
        }

        $schedule->delete();

        return redirect()
            ->route('panitia.interviews.index', $request->only(['q', 'status', 'ta', 'page']))
            ->with('status', 'Jadwal wawancara berhasil dihapus.');
    }

    public function assign(Request $request, StudentRegistration $registration): RedirectResponse
    {
        if (! $registration->submitted_at) {
            return redirect()
                ->route('panitia.interviews.index')
                ->withErrors([
                    'interview_schedule_key' => 'Jadwal hanya bisa diatur untuk orang tua yang sudah mengirim formulir.',
                ]);
        }

        if ($registration->interview_selected_at) {
            return redirect()
                ->route('panitia.interviews.index')
                ->withErrors([
                    'interview_schedule_key' => 'Jadwal untuk pendaftar ini sudah dipilih dan tidak dapat diubah.',
                ]);
        }

        $scheduleOptions = $this->getInterviewScheduleOptions();

        $validated = $request->validate([
            'interview_schedule_key' => ['required', 'string', 'in:'.implode(',', array_keys($scheduleOptions))],
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
            ->route('panitia.interviews.index', $request->only(['q', 'status', 'ta', 'page']))
            ->with('status', 'Jadwal wawancara berhasil disimpan.');
    }

    public function updateStatus(Request $request, StudentRegistration $registration): RedirectResponse
    {
        if (! $registration->interview_selected_at) {
            return redirect()
                ->route('panitia.interviews.index', $request->only(['q', 'status', 'ta', 'page']))
                ->withErrors([
                    'interview_status' => 'Status wawancara hanya bisa diubah setelah jadwal dipilih.',
                ]);
        }

        if ($registration->interview_completed_at || filled($registration->interview_notes)) {
            return redirect()
                ->route('panitia.interviews.index', $request->only(['q', 'status', 'ta', 'page']))
                ->withErrors([
                    'interview_status' => 'Catatan atau status wawancara yang sudah disimpan tidak bisa diubah lagi.',
                ]);
        }

        $validated = $request->validate([
            'interview_status' => ['required', 'in:selesai,belum_selesai,simpan_catatan'],
            'interview_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $updates = [
            'interview_notes' => $validated['interview_notes'] ?? null,
        ];

        if ($validated['interview_status'] === 'selesai') {
            $updates['interview_completed_at'] = $registration->interview_completed_at ?: now();
        }

        if ($validated['interview_status'] === 'belum_selesai') {
            $updates['interview_completed_at'] = null;
        }

        $registration->forceFill($updates)->save();

        return redirect()
            ->route('panitia.interviews.index', $request->only(['q', 'status', 'ta', 'page']))
            ->with('status', match ($validated['interview_status']) {
                'selesai' => 'Calon siswa ditandai sudah selesai wawancara.',
                'belum_selesai' => 'Status wawancara calon siswa dikembalikan menjadi belum selesai.',
                default => 'Catatan hasil wawancara berhasil disimpan.',
            });
    }

    public function export(Request $request): Response
    {
        Carbon::setLocale('id');

        $search = trim((string) $request->string('q'));
        $status = (string) $request->string('status');
        $academicYear = (string) $request->string('ta');

        $rows = StudentRegistration::query()
            ->with('user')
            ->whereNotNull('submitted_at')
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (StudentRegistration $registration) => $this->decorateRegistration($registration))
            ->filter(function (StudentRegistration $registration) use ($search) {
                if ($search === '') {
                    return true;
                }

                $haystack = collect([
                    $registration->full_name,
                    $registration->registration_number,
                    $registration->user?->name,
                    $registration->user?->email,
                ])->filter()->implode(' ');

                return str_contains(strtolower($haystack), strtolower($search));
            })
            ->when($status !== '', fn (Collection $items) => $items->where('display_interview_status', $status))
            ->when($academicYear !== '', fn (Collection $items) => $items->where('display_academic_year', $academicYear))
            ->values();

        $exportRows = collect([
            ['No. Registrasi', 'Nama Siswa', 'Nama Orang Tua', 'Email Orang Tua', 'Tahun Ajaran', 'Status Jadwal', 'Status Wawancara', 'Catatan Hasil Wawancara', 'Hari Wawancara', 'Tanggal Wawancara', 'Jam Wawancara', 'Ruangan', 'Dipilih Pada', 'Selesai Pada', 'Tanggal Submit Formulir'],
        ])->concat(
            $rows->map(function (StudentRegistration $registration) {
                return [
                    $registration->registration_number ?? '-',
                    $registration->full_name ?? '-',
                    $registration->user?->name ?? '-',
                    $registration->user?->email ?? '-',
                    $registration->display_academic_year ?? '-',
                    $registration->display_interview_status_label ?? '-',
                    $registration->display_interview_completion_label ?? '-',
                    $registration->interview_notes ?: '-',
                    $registration->interview_day_name ?? '-',
                    $registration->interview_date ? $registration->interview_date->translatedFormat('d F Y') : '-',
                    $registration->interview_time ?? '-',
                    $registration->interview_room ?? '-',
                    $registration->interview_selected_at ? $registration->interview_selected_at->format('d-m-Y H:i').' WIB' : '-',
                    $registration->interview_completed_at ? $registration->interview_completed_at->format('d-m-Y H:i').' WIB' : '-',
                    $registration->submitted_at ? $registration->submitted_at->translatedFormat('d F Y H:i').' WIB' : '-',
                ];
            })
        );

        $tableRows = $exportRows
            ->map(function (array $columns, int $index) {
                $tag = $index === 0 ? 'th' : 'td';
                $cells = collect($columns)
                    ->map(fn ($value) => '<'.$tag.'>'.e((string) $value).'</'.$tag.'>')
                    ->implode('');

                return '<tr>'.$cells.'</tr>';
            })
            ->implode('');

        $excel = '<html><head><meta charset="UTF-8"></head><body><table border="1">'.$tableRows.'</table></body></html>';
        $fileName = 'data-jadwal-wawancara-'.now()->format('Ymd-His').'.xls';

        return response($excel, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ]);
    }

    private function decorateRegistration(StudentRegistration $registration): StudentRegistration
    {
        $registration->display_academic_year = $this->resolveAcademicYear($registration);
        $registration->display_interview_status = $registration->interview_selected_at ? 'sudah_pilih' : 'belum_pilih';
        $registration->display_interview_status_label = $registration->interview_selected_at ? 'Sudah Memilih' : 'Belum Memilih';
        $registration->display_interview_status_tone = $registration->interview_selected_at ? 'emerald' : 'amber';
        $registration->display_interview_completion_status = $registration->interview_completed_at ? 'selesai' : 'belum_selesai';
        $registration->display_interview_completion_label = $registration->interview_completed_at ? 'Selesai Wawancara' : 'Belum Selesai';
        $registration->display_interview_completion_tone = $registration->interview_completed_at ? 'blue' : 'slate';

        return $registration;
    }

    private function resolveAcademicYear(StudentRegistration $registration): string
    {
        $baseDate = $registration->submitted_at ?? $registration->created_at ?? now();
        $year = (int) $baseDate->format('Y');

        if ((int) $baseDate->format('n') < 7) {
            return ($year - 1).'/'.$year;
        }

        return $year.'/'.($year + 1);
    }

    private function getInterviewScheduleOptions(): array
    {
        Carbon::setLocale('id');

        if (! Schema::hasTable('interview_schedules')) {
            return $this->defaultInterviewScheduleOptions();
        }

        return InterviewSchedule::query()
            ->where('is_active', true)
            ->whereBetween('interview_date', [self::SCHEDULE_START_DATE, self::SCHEDULE_END_DATE])
            ->orderBy('interview_date')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->reject(fn (InterviewSchedule $schedule) => $schedule->interview_date->isSunday())
            ->mapWithKeys(function (InterviewSchedule $schedule) {
                return [
                    $schedule->schedule_key => [
                        'key' => $schedule->schedule_key,
                        'session_label' => $schedule->session_label,
                        'date' => $schedule->interview_date->copy(),
                        'day_name' => Str::headline($schedule->interview_date->translatedFormat('l')),
                        'formatted_date' => $schedule->interview_date->translatedFormat('d F Y'),
                        'time' => $schedule->interview_time,
                        'room' => $schedule->room,
                    ],
                ];
            })
            ->all();
    }

    private function defaultInterviewScheduleOptions(): array
    {
        $options = [];
        $date = $this->scheduleStartDate();
        $end = $this->scheduleEndDate();

        while ($date->lte($end)) {
            if ($date->isSunday()) {
                $date->addDay();
                continue;
            }

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

    private function validateSchedule(Request $request): array
    {
        return $request->validate([
            'interview_date' => ['required', 'date'],
            'session_label' => ['required', 'string', 'max:100'],
            'interview_time' => ['required', 'string', 'max:100'],
            'room' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function isAllowedScheduleDate(Carbon $date): bool
    {
        return $date->betweenIncluded($this->scheduleStartDate(), $this->scheduleEndDate())
            && ! $date->isSunday();
    }

    private function scheduleDateError(Request $request): RedirectResponse
    {
        return redirect()
            ->route('panitia.interviews.index', $request->only(['q', 'status', 'ta', 'page']))
            ->withErrors(['schedule' => 'Jadwal hanya boleh dibuat dari 1 Oktober 2025 sampai 31 Juli 2026, dan hari Minggu tidak dapat dipilih.']);
    }

    private function scheduleStartDate(): Carbon
    {
        return Carbon::parse(self::SCHEDULE_START_DATE)->startOfDay();
    }

    private function scheduleEndDate(): Carbon
    {
        return Carbon::parse(self::SCHEDULE_END_DATE)->endOfDay();
    }

    private function generateScheduleKey(Carbon $date, string $time, string $room): string
    {
        $base = $date->toDateString().'-'.Str::slug($time.'-'.$room);
        $key = $base;
        $counter = 2;

        while (InterviewSchedule::where('schedule_key', $key)->exists()) {
            $key = $base.'-'.$counter;
            $counter++;
        }

        return $key;
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
