<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\AnnualStudentCount;
use App\Models\PpdbFormPayment;
use App\Models\SchoolContent;
use App\Models\StudentRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PanitiaDashboardController extends Controller
{
    public function index(): View
    {
        $classColumns = $this->getStudentClassColumns();
        $dashboardRegistrations = StudentRegistration::query()
            ->select([
                'id',
                'created_at',
                'gender',
                'birth_date',
                'father_job',
                'mother_job',
                'father_education',
                'mother_education',
                'father_income',
                'mother_income',
                'origin_region',
                'verification_status',
                'submitted_at',
                'interview_selected_at',
                'selection_result',
                'interview_room',
                'reregistration_status',
                'reregistration_paid_at',
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
                'ageDistribution' => $this->buildAgeDistributionChart($dashboardRegistrations),
                'motherJobs' => $this->buildSingleFieldDistributionChart($dashboardRegistrations, 'mother_job', 'pekerjaan ibu'),
                'fatherJobs' => $this->buildSingleFieldDistributionChart($dashboardRegistrations, 'father_job', 'pekerjaan ayah'),
                'parentIncomes' => $this->buildParentFieldDistributionChart($dashboardRegistrations, ['father_income', 'mother_income'], 'penghasilan orang tua'),
                'parentEducations' => $this->buildParentFieldDistributionChart($dashboardRegistrations, ['father_education', 'mother_education'], 'pendidikan orang tua'),
                'applicantStatus' => $this->buildApplicantStatusChart($dashboardRegistrations),
                'monthlyTrend' => $this->buildMonthlyRegistrationTrend($dashboardRegistrations),
                'verification' => $this->buildVerificationChart($dashboardRegistrations),
                'classQuota' => $this->buildClassQuotaChart($dashboardRegistrations, $classColumns),
                'regions' => $this->buildRegionTreemapChart($dashboardRegistrations),
                'annualRegistrations' => $this->buildAnnualRegistrationChart(),
                'reRegistration' => $this->buildReRegistrationChart($dashboardRegistrations),
            ],
            'classQuota' => $this->buildClassQuotaSummary($dashboardRegistrations),
            'summaryCards' => $this->buildDashboardSummaryCards($dashboardRegistrations),
            'stageProgress' => $this->buildStageProgressSummary($dashboardRegistrations),
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

    private function buildClassQuotaSummary(Collection $registrations): array
    {
        $capacity = 90;
        $filled = $registrations->where('selection_result', 'lulus')->count();
        $remaining = max($capacity - $filled, 0);
        $percentage = $capacity > 0 ? round(($filled / $capacity) * 100, 1) : 0;

        return [
            'capacity' => $capacity,
            'filled' => $filled,
            'remaining' => $remaining,
            'percentage' => $percentage,
            'is_full' => $filled >= $capacity,
            'academic_year' => '2026/2027',
        ];
    }

    private function buildStageProgressSummary(Collection $registrations): array
    {
        $paidFormCount = PpdbFormPayment::query()
            ->whereIn('status', ['settlement', 'capture'])
            ->whereNotNull('paid_at')
            ->count();
        $completedRegistrationCount = $registrations->whereNotNull('submitted_at')->count();
        $interviewSelectedCount = $registrations->whereNotNull('interview_selected_at')->count();
        $acceptedCount = $registrations->where('selection_result', 'lulus')->count();
        $paidReRegistrationCount = $registrations
            ->filter(fn (StudentRegistration $registration) => $registration->reregistration_paid_at
                && in_array($registration->reregistration_status, ['settlement', 'capture'], true))
            ->count();
        $total = max($registrations->count(), $paidFormCount, 1);

        return [
            [
                'label' => 'Pembelian formulir lunas',
                'count' => $paidFormCount,
                'percentage' => $this->calculateDashboardPercentage($paidFormCount, $total),
            ],
            [
                'label' => 'Unggah formulir dan berkas lengkap',
                'count' => $completedRegistrationCount,
                'percentage' => $this->calculateDashboardPercentage($completedRegistrationCount, $total),
            ],
            [
                'label' => 'Wawancara yang telah dipilih',
                'count' => $interviewSelectedCount,
                'percentage' => $this->calculateDashboardPercentage($interviewSelectedCount, $total),
            ],
            [
                'label' => 'Diterima',
                'count' => $acceptedCount,
                'percentage' => $this->calculateDashboardPercentage($acceptedCount, $total),
            ],
            [
                'label' => 'Pembayaran lunas',
                'count' => $paidReRegistrationCount,
                'percentage' => $this->calculateDashboardPercentage($paidReRegistrationCount, $total),
            ],
        ];
    }

    private function buildDashboardSummaryCards(Collection $registrations): array
    {
        $totalRegistrations = $registrations->count();
        $acceptedRegistrations = $registrations->where('selection_result', 'lulus');
        $paidAcceptedRegistrations = $acceptedRegistrations
            ->filter(fn (StudentRegistration $registration) => $registration->reregistration_paid_at
                && in_array($registration->reregistration_status, ['settlement', 'capture'], true));
        $maleCount = $registrations->where('gender', 'Laki-laki')->count();
        $femaleCount = $registrations->where('gender', 'Perempuan')->count();
        $genderTotal = $maleCount + $femaleCount;
        $undecidedCount = $registrations
            ->filter(fn (StudentRegistration $registration) => blank($registration->selection_result))
            ->count();

        return [
            'graduation' => [
                'percentage' => $this->formatDashboardPercentage($acceptedRegistrations->count(), $totalRegistrations),
                'detail' => "{$acceptedRegistrations->count()} dari {$totalRegistrations} pendaftar diterima",
            ],
            'payment' => [
                'percentage' => $this->formatDashboardPercentage($paidAcceptedRegistrations->count(), $totalRegistrations),
                'detail' => "{$paidAcceptedRegistrations->count()} siswa diterima sudah melunasi pembayaran",
            ],
            'gender' => [
                'ratio' => "{$maleCount} : {$femaleCount}",
                'detail' => $genderTotal > 0
                    ? $this->formatDashboardPercentage($maleCount, $genderTotal).' laki-laki'
                    : 'Belum ada data jenis kelamin',
            ],
            'undecided' => [
                'percentage' => $this->formatDashboardPercentage($undecidedCount, $totalRegistrations),
                'detail' => "{$undecidedCount} pendaftar belum diputuskan",
            ],
        ];
    }

    private function calculateDashboardPercentage(int $value, int $total): float
    {
        if ($total <= 0) {
            return 0;
        }

        return round(($value / $total) * 100, 1);
    }

    private function formatDashboardPercentage(int $value, int $total): string
    {
        $percentage = $this->calculateDashboardPercentage($value, $total);
        $formatted = number_format($percentage, 1, ',', '.');

        return str_ends_with($formatted, ',0')
            ? substr($formatted, 0, -2).'%'
            : $formatted.'%';
    }

    public function updateAnnualStudentCounts(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'counts' => ['required', 'array'],
            'counts.*' => ['nullable', 'integer', 'min:0'],
        ]);

        foreach (range(2019, 2025) as $year) {
            AnnualStudentCount::updateOrCreate(
                ['year' => $year],
                ['total' => (int) ($validated['counts'][$year] ?? 0)],
            );
        }

        return back()->with('status', 'Jumlah siswa manual berhasil disimpan.');
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

    private function buildAgeDistributionChart(Collection $registrations): array
    {
        $labels = ['4 Tahun', '5 Tahun', '< 6 Tahun', '6 Tahun'];
        $counts = array_fill_keys($labels, 0);
        $filledRegistrations = $registrations->filter(fn (StudentRegistration $registration) => $registration->birth_date !== null);

        foreach ($filledRegistrations as $registration) {
            $age = $registration->birth_date->age;

            if ($age === 4) {
                $counts['4 Tahun']++;
                continue;
            }

            if ($age === 5) {
                $counts['5 Tahun']++;
                continue;
            }

            if ($age < 6) {
                $counts['< 6 Tahun']++;
                continue;
            }

            if ($age === 6) {
                $counts['6 Tahun']++;
            }
        }

        $total = $filledRegistrations->count();
        $majorityLabel = collect($counts)->sortDesc()->keys()->first() ?? '< 6 Tahun';
        $majorityCount = $counts[$majorityLabel] ?? 0;

        return [
            'labels' => array_keys($counts),
            'series' => array_values($counts),
            'total' => $total,
            'majorityLabel' => $majorityLabel,
            'majorityPercentage' => $this->formatDashboardPercentage($majorityCount, $total),
            'note' => $total > 0
                ? "Mayoritas usia pendaftar adalah {$majorityLabel} ({$this->formatDashboardPercentage($majorityCount, $total)} dari data terisi)."
                : 'Usia pendaftar akan tampil setelah data tanggal lahir tersedia.',
        ];
    }

    private function buildSingleFieldDistributionChart(Collection $registrations, string $field, string $subject): array
    {
        $values = $registrations
            ->map(fn (StudentRegistration $registration) => $this->normalizeDashboardLabel($registration->{$field} ?? null))
            ->filter()
            ->values();

        return $this->buildDistributionPayload($values, $subject);
    }

    private function buildParentFieldDistributionChart(Collection $registrations, array $fields, string $subject): array
    {
        $values = $registrations
            ->flatMap(fn (StudentRegistration $registration) => collect($fields)
                ->map(fn (string $field) => $this->normalizeDashboardLabel($registration->{$field} ?? null)))
            ->filter()
            ->values();

        return $this->buildDistributionPayload($values, $subject);
    }

    private function buildDistributionPayload(Collection $values, string $subject): array
    {
        $counts = $values->countBy()->sortDesc();
        $total = $values->count();
        $majorityLabel = $counts->keys()->first() ?? '-';
        $majorityCount = (int) ($counts->first() ?? 0);

        return [
            'labels' => $counts->keys()->values()->all(),
            'series' => $counts->values()->map(fn ($count) => (int) $count)->all(),
            'total' => $total,
            'note' => $total > 0
                ? "Mayoritas pada {$subject} pendaftar adalah {$majorityLabel} ({$this->formatDashboardPercentage($majorityCount, $total)} dari data terisi)."
                : "Data {$subject} akan tampil setelah biodata orang tua terisi.",
        ];
    }

    private function normalizeDashboardLabel(?string $value): ?string
    {
        $normalized = trim((string) $value);

        if ($normalized === '') {
            return null;
        }

        return Str::headline(str_replace(['_', '-'], ' ', $normalized));
    }

    private function buildApplicantStatusChart(Collection $registrations): array
    {
        $accepted = $registrations->where('selection_result', 'lulus')->count();
        $rejected = $registrations
            ->filter(fn (StudentRegistration $registration) => $registration->selection_result === 'tidak_lulus'
                || $registration->verification_status === 'ditolak')
            ->count();
        $waiting = max($registrations->count() - $accepted - $rejected, 0);

        return [
            'labels' => ['Diterima', 'Menunggu', 'Ditolak'],
            'series' => [$accepted, $waiting, $rejected],
            'note' => "Diterima {$accepted}, menunggu {$waiting}, ditolak {$rejected} pendaftar.",
        ];
    }

    private function buildMonthlyRegistrationTrend(Collection $registrations): array
    {
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $counts = array_fill(1, 12, 0);

        foreach ($registrations as $registration) {
            if (! $registration->created_at) {
                continue;
            }

            $counts[(int) $registration->created_at->format('n')]++;
        }

        $series = array_values($counts);
        $peakCount = max($series);
        $peakIndex = array_search($peakCount, $series, true);
        $peakMonth = $peakIndex === false ? '-' : $monthLabels[$peakIndex];

        return [
            'labels' => $monthLabels,
            'series' => $series,
            'note' => $peakCount > 0
                ? "Puncak pendaftaran terjadi pada bulan {$peakMonth} dengan {$peakCount} pendaftar."
                : 'Tren pendaftaran bulanan akan tampil setelah data pendaftar tersedia.',
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

    private function buildReRegistrationChart(Collection $registrations): array
    {
        $acceptedRegistrations = $registrations->where('selection_result', 'lulus');
        $paid = $acceptedRegistrations
            ->filter(fn (StudentRegistration $registration) => $registration->reregistration_paid_at
                && in_array($registration->reregistration_status, ['settlement', 'capture'], true))
            ->count();

        return [
            'labels' => ['Sudah Daftar Ulang', 'Belum Daftar Ulang'],
            'series' => [
                $paid,
                max($acceptedRegistrations->count() - $paid, 0),
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

    private function buildAnnualRegistrationChart(): array
    {
        $manualCounts = Schema::hasTable('annual_student_counts')
            ? AnnualStudentCount::query()
                ->whereBetween('year', [2019, 2025])
                ->pluck('total', 'year')
                ->mapWithKeys(fn ($total, $year) => [(int) $year => (int) $total])
                ->all()
            : [];
        $systemCounts = StudentRegistration::query()
            ->whereBetween('created_at', [
                Carbon::create(2026, 1, 1)->startOfDay(),
                Carbon::create(2027, 12, 31)->endOfDay(),
            ])
            ->get(['created_at'])
            ->groupBy(fn (StudentRegistration $registration) => (int) $registration->created_at->format('Y'))
            ->map(fn (Collection $items) => $items->count())
            ->all();

        $years = range(2019, 2027);

        return [
            'labels' => array_map(fn (int $year) => (string) $year, $years),
            'series' => array_map(function (int $year) use ($manualCounts, $systemCounts) {
                if ($year >= 2026) {
                    return $systemCounts[$year] ?? 0;
                }

                return (int) ($manualCounts[$year] ?? 0);
            }, $years),
            'manualInputs' => collect(range(2019, 2025))
                ->mapWithKeys(fn (int $year) => [$year => (int) ($manualCounts[$year] ?? 0)])
                ->all(),
            'manualYears' => ['2019-2025'],
            'systemYears' => ['2026-2027'],
        ];
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
