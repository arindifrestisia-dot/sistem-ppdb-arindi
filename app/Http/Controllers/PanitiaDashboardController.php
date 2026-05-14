<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SchoolContent;
use App\Models\StudentRegistration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PanitiaDashboardController extends Controller
{
    public function index(): View
    {
        $classColumns = $this->getStudentClassColumns();
        $dashboardRegistrations = StudentRegistration::query()
            ->select([
                'id',
                'gender',
                'birth_date',
                'origin_region',
                'verification_status',
                'selection_result',
                'interview_room',
                ...$classColumns,
            ])
            ->get();

        return view('dashboard.panitia.home', [
            'stats' => [
                'total_pendaftar' => $dashboardRegistrations->count(),
                'berkas_menunggu' => $dashboardRegistrations->where('verification_status', 'belum_diperiksa')->count(),
                'berkas_terverifikasi' => $dashboardRegistrations->where('verification_status', 'terverifikasi')->count(),
                'total_lulus' => $dashboardRegistrations->where('selection_result', 'lulus')->count(),
                'total_tidak_lulus' => $dashboardRegistrations->where('selection_result', 'tidak_lulus')->count(),
            ],
            'chartData' => [
                'gender' => $this->buildGenderChart($dashboardRegistrations),
                'verification' => $this->buildVerificationChart($dashboardRegistrations),
                'classQuota' => $this->buildClassQuotaChart($dashboardRegistrations, $classColumns),
                'regions' => $this->buildRegionTreemapChart($dashboardRegistrations),
            ],
            'recentRegistrations' => StudentRegistration::with('user')
                ->latest()
                ->limit(5)
                ->get(),
            'contentSummary' => collect(SchoolContent::typeOptions())
                ->mapWithKeys(fn (string $label, string $type) => [
                    $type => [
                        'label' => $label,
                        'count' => SchoolContent::where('type', $type)->count(),
                    ],
                ]),
        ]);
    }

    private function buildGenderChart(Collection $registrations): array
    {
        return [
            'labels' => ['Perempuan', 'Laki-laki'],
            'series' => [
                $registrations->where('gender', 'Perempuan')->count(),
                $registrations->where('gender', 'Laki-laki')->count(),
            ],
        ];
    }

    private function buildVerificationChart(Collection $registrations): array
    {
        return [
            'labels' => ['Terverifikasi', 'Dalam Proses', 'Ditolak'],
            'series' => [
                $registrations->where('verification_status', 'terverifikasi')->count(),
                $registrations->whereIn('verification_status', ['belum_diperiksa', 'revisi'])->count(),
                $registrations->where('verification_status', 'ditolak')->count(),
            ],
        ];
    }

    private function buildClassQuotaChart(Collection $registrations, array $classColumns): array
    {
        $distribution = [
            'A' => 0,
            'B1' => 0,
            'B2' => 0,
            'B3' => 0,
        ];

        foreach ($registrations as $registration) {
            $distribution[$this->resolveClassLabel($registration, $classColumns)]++;
        }

        $totalStudents = array_sum($distribution);
        $divisor = max($totalStudents, 1);

        return [
            'labels' => array_keys($distribution),
            'counts' => array_values($distribution),
            'series' => array_map(
                fn (int $count) => round(($count / $divisor) * 100, 1),
                array_values($distribution),
            ),
            'note' => empty($classColumns)
                ? 'Persentase kelas dihitung otomatis dari usia siswa dan ruang wawancara karena kolom kelas final belum tersedia di database.'
                : 'Persentase kelas diambil langsung dari data kelas yang tersimpan pada database.',
        ];
    }

    private function buildRegionTreemapChart(Collection $registrations): array
    {
        return $registrations
            ->groupBy(function ($registration) {
                $region = trim((string) $registration->origin_region);

                return $region !== '' ? $region : 'Tidak diketahui';
            })
            ->map(fn (Collection $items, string $region) => [
                'x' => $region,
                'y' => $items->count(),
            ])
            ->sortByDesc('y')
            ->values()
            ->all();
    }

    private function getStudentClassColumns(): array
    {
        return collect([
            'class_name',
            'class_group',
            'assigned_class',
            'target_class',
        ])
            ->filter(fn (string $column) => Schema::hasColumn('student_registrations', $column))
            ->values()
            ->all();
    }

    private function resolveClassLabel(StudentRegistration $registration, array $classColumns): string
    {
        foreach ($classColumns as $column) {
            $value = $this->normalizeClassLabel((string) ($registration->{$column} ?? ''));

            if ($value !== null) {
                return $value;
            }
        }

        if ($registration->birth_date && Carbon::parse($registration->birth_date)->diffInYears($this->getAcademicCutoffDate()) < 5) {
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

    private function normalizeClassLabel(string $value): ?string
    {
        $normalized = strtoupper(str_replace([' ', '-'], '', trim($value)));

        return match ($normalized) {
            'A', 'KELASA', 'KELOMPOKA' => 'A',
            'B1', 'KELASB1', 'KELOMPOKB1' => 'B1',
            'B2', 'KELASB2', 'KELOMPOKB2' => 'B2',
            'B3', 'KELASB3', 'KELOMPOKB3' => 'B3',
            default => null,
        };
    }

    private function getAcademicCutoffDate(): Carbon
    {
        return now()->copy()->startOfYear()->addMonths(6);
    }
}
