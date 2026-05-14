<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\StudentRegistration;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PanitiaRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $segment = (string) $request->string('segment', 'saat_ini');
        $class = (string) $request->string('class');
        $academicYear = (string) $request->string('ta');

        $allRegistrations = StudentRegistration::query()
            ->with(['user', 'verifier'])
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->get()
            ->map(function (StudentRegistration $registration) {
                $registration->display_class = $this->resolveClassLabel($registration);
                $registration->display_academic_year = $this->resolveAcademicYear($registration);

                return $registration;
            });

        $segmentOptions = $this->segmentOptions();
        abort_unless(array_key_exists($segment, $segmentOptions), 404);

        $filteredRegistrations = $allRegistrations
            ->filter(fn (StudentRegistration $registration) => $this->matchesSegment($registration, $segment))
            ->filter(function (StudentRegistration $registration) use ($search) {
                if ($search === '') {
                    return true;
                }

                $haystack = collect([
                    $registration->full_name,
                    $registration->registration_number,
                    $registration->user?->email,
                ])->filter()->implode(' ');

                return str_contains(strtolower($haystack), strtolower($search));
            })
            ->when($class !== '', fn (Collection $items) => $items->where('display_class', $class))
            ->when($academicYear !== '', fn (Collection $items) => $items->where('display_academic_year', $academicYear))
            ->values();

        $registrations = $this->paginateCollection($filteredRegistrations, $request, 10);

        return view('dashboard.panitia.registrations.index', [
            'registrations' => $registrations,
            'search' => $search,
            'segment' => $segment,
            'class' => $class,
            'academicYear' => $academicYear,
            'segmentOptions' => $segmentOptions,
            'classOptions' => $allRegistrations->pluck('display_class')->filter()->unique()->sort()->values(),
            'academicYearOptions' => $allRegistrations->pluck('display_academic_year')->filter()->unique()->sortDesc()->values(),
        ]);
    }

    public function export(Request $request): Response
    {
        $search = trim((string) $request->string('q'));
        $segment = (string) $request->string('segment', 'saat_ini');
        $class = (string) $request->string('class');
        $academicYear = (string) $request->string('ta');

        abort_unless(array_key_exists($segment, $this->segmentOptions()), 404);

        $rows = StudentRegistration::query()
            ->with('user')
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->get()
            ->map(function (StudentRegistration $registration) {
                $registration->display_class = $this->resolveClassLabel($registration);
                $registration->display_academic_year = $this->resolveAcademicYear($registration);

                return $registration;
            })
            ->filter(fn (StudentRegistration $registration) => $this->matchesSegment($registration, $segment))
            ->filter(function (StudentRegistration $registration) use ($search) {
                if ($search === '') {
                    return true;
                }

                $haystack = collect([
                    $registration->full_name,
                    $registration->registration_number,
                    $registration->user?->email,
                ])->filter()->implode(' ');

                return str_contains(strtolower($haystack), strtolower($search));
            })
            ->when($class !== '', fn (Collection $items) => $items->where('display_class', $class))
            ->when($academicYear !== '', fn (Collection $items) => $items->where('display_academic_year', $academicYear))
            ->values();

        $csv = collect([
            ['NIS', 'Nama Siswa', 'Kelas', 'Jenis Kelamin', 'Tahun Ajaran', 'Status Verifikasi', 'Hasil Seleksi', 'Email Orang Tua'],
        ])->concat(
            $rows->map(fn (StudentRegistration $registration) => [
                $registration->registration_number ?? '-',
                $registration->full_name,
                $registration->display_class,
                $registration->gender,
                $registration->display_academic_year,
                str_replace('_', ' ', (string) $registration->verification_status),
                $registration->selection_result ? str_replace('_', ' ', $registration->selection_result) : '-',
                $registration->user?->email ?? '-',
            ])
        )->map(fn (array $columns) => implode(',', array_map(fn ($value) => '"' . str_replace('"', '""', (string) $value) . '"', $columns)))
            ->implode("\n");

        $fileName = 'data-siswa-' . $segment . '-' . now()->format('Ymd-His') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function show(StudentRegistration $registration): View
    {
        $registration->load(['user', 'verifier']);

        return view('dashboard.panitia.registrations.show', [
            'registration' => $registration,
        ]);
    }

    public function update(Request $request, StudentRegistration $registration): RedirectResponse
    {
        $validated = $request->validate([
            'verification_status' => ['required', 'in:belum_diperiksa,revisi,terverifikasi,ditolak'],
            'verification_notes' => ['nullable', 'string'],
            'selection_result' => ['nullable', 'in:lulus,tidak_lulus'],
            'publish_selection' => ['nullable', 'boolean'],
        ]);

        $registration->fill([
            'verification_status' => $validated['verification_status'],
            'verification_notes' => $validated['verification_notes'] ?? null,
            'selection_result' => $validated['selection_result'] ?? null,
        ]);

        $registration->verified_by = $request->user()->id;
        $registration->verified_at = now();
        $registration->selection_published_at = $request->boolean('publish_selection') && ! empty($validated['selection_result'])
            ? now()
            : null;
        $registration->save();

        return redirect()
            ->route('panitia.registrations.show', array_filter([
                'registration' => $registration,
                'segment' => $request->input('segment'),
                'q' => $request->input('q'),
                'class' => $request->input('class'),
                'ta' => $request->input('ta'),
            ], fn ($value) => $value !== null && $value !== ''))
            ->with('status', 'Data pendaftaran berhasil diperbarui.');
    }

    private function segmentOptions(): array
    {
        return [
            'saat_ini' => 'Data Siswa Saat Ini',
            'calon' => 'Data Calon Siswa',
            'daftar_ulang' => 'Daftar Ulang',
            'ditolak' => 'Siswa Ditolak',
        ];
    }

    private function matchesSegment(StudentRegistration $registration, string $segment): bool
    {
        return match ($segment) {
            'saat_ini' => $registration->selection_result === 'lulus',
            'calon' => $registration->selection_result === null && $registration->verification_status !== 'ditolak',
            'daftar_ulang' => $registration->selection_result === 'lulus' && $registration->selection_published_at !== null,
            'ditolak' => $registration->selection_result === 'tidak_lulus' || $registration->verification_status === 'ditolak',
            default => false,
        };
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

    private function resolveAcademicYear(StudentRegistration $registration): string
    {
        $baseDate = $registration->submitted_at ?? $registration->created_at ?? now();
        $year = (int) $baseDate->format('Y');

        if ((int) $baseDate->format('n') < 7) {
            return ($year - 1) . '/' . $year;
        }

        return $year . '/' . ($year + 1);
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
