<?php

namespace App\Http\Controllers;

use App\Models\StudentRegistration;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PanitiaInterviewScheduleController extends Controller
{
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

        $validated = $request->validate([
            'interview_status' => ['required', 'in:selesai,belum_selesai'],
        ]);

        $registration->forceFill([
            'interview_completed_at' => $validated['interview_status'] === 'selesai' ? now() : null,
        ])->save();

        return redirect()
            ->route('panitia.interviews.index', $request->only(['q', 'status', 'ta', 'page']))
            ->with('status', $validated['interview_status'] === 'selesai'
                ? 'Calon siswa ditandai sudah selesai wawancara.'
                : 'Status wawancara calon siswa dikembalikan menjadi belum selesai.');
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

        $csv = collect([
            ['No. Registrasi', 'Nama Siswa', 'Nama Orang Tua', 'Email Orang Tua', 'Tahun Ajaran', 'Status Jadwal', 'Status Wawancara', 'Hari Wawancara', 'Tanggal Wawancara', 'Jam Wawancara', 'Ruangan', 'Dipilih Pada', 'Selesai Pada', 'Tanggal Submit Formulir'],
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
                    $registration->interview_day_name ?? '-',
                    $registration->interview_date ? $registration->interview_date->translatedFormat('d F Y') : '-',
                    $registration->interview_time ?? '-',
                    $registration->interview_room ?? '-',
                    $registration->interview_selected_at ? $registration->interview_selected_at->format('d-m-Y H:i') . ' WIB' : '-',
                    $registration->interview_completed_at ? $registration->interview_completed_at->format('d-m-Y H:i') . ' WIB' : '-',
                    $registration->submitted_at ? $registration->submitted_at->translatedFormat('d F Y H:i') . ' WIB' : '-',
                ];
            })
        )->map(fn (array $columns) => implode(',', array_map(fn ($value) => '"' . str_replace('"', '""', (string) $value) . '"', $columns)))
            ->implode("\n");

        $fileName = 'data-jadwal-wawancara-' . now()->format('Ymd-His') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
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
            return ($year - 1) . '/' . $year;
        }

        return $year . '/' . ($year + 1);
    }

    private function getInterviewScheduleOptions(): array
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
