<?php

namespace App\Http\Controllers;

use App\Models\StudentRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PanitiaFormSaleController extends Controller
{
    private const FORM_PRICE = 100000;

    public function index(Request $request): View
    {
        Carbon::setLocale('id');

        $search = trim((string) $request->string('q'));
        $status = (string) $request->string('status');
        $academicYear = (string) $request->string('ta');

        $allForms = StudentRegistration::query()
            ->with('user')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (StudentRegistration $registration) => $this->decorateFormSale($registration));

        $filteredForms = $allForms
            ->filter(function (StudentRegistration $registration) use ($search) {
                if ($search === '') {
                    return true;
                }

                $haystack = collect([
                    $registration->display_form_number,
                    $registration->display_buyer_name,
                    $registration->display_student_name,
                    $registration->user?->email,
                ])->filter()->implode(' ');

                return str_contains(strtolower($haystack), strtolower($search));
            })
            ->when($status !== '', fn (Collection $items) => $items->where('display_status_key', $status))
            ->when($academicYear !== '', fn (Collection $items) => $items->where('display_academic_year', $academicYear))
            ->values();

        $forms = $this->paginateCollection($filteredForms, $request, 10);
        $completedForms = $allForms->where('display_status_key', 'lengkap')->count();
        $completionRate = $allForms->count() > 0
            ? round(($completedForms / $allForms->count()) * 100, 1)
            : 0;

        return view('dashboard.panitia.forms.index', [
            'forms' => $forms,
            'search' => $search,
            'status' => $status,
            'academicYear' => $academicYear,
            'statusOptions' => [
                'lengkap' => 'Diisi Lengkap',
                'belum_lengkap' => 'Belum Lengkap',
                'belum_diisi' => 'Belum Diisi',
            ],
            'academicYearOptions' => $allForms->pluck('display_academic_year')->filter()->unique()->sortDesc()->values(),
            'stats' => [
                'total_forms' => $allForms->count(),
                'completed_forms' => $completedForms,
                'completion_rate' => $completionRate,
                'current_academic_year' => $academicYear !== '' ? $academicYear : ($allForms->pluck('display_academic_year')->filter()->first() ?? $this->resolveAcademicYear(now())),
            ],
        ]);
    }

    public function export(Request $request): Response
    {
        Carbon::setLocale('id');

        $search = trim((string) $request->string('q'));
        $status = (string) $request->string('status');
        $academicYear = (string) $request->string('ta');

        $rows = StudentRegistration::query()
            ->with('user')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (StudentRegistration $registration) => $this->decorateFormSale($registration))
            ->filter(function (StudentRegistration $registration) use ($search) {
                if ($search === '') {
                    return true;
                }

                $haystack = collect([
                    $registration->display_form_number,
                    $registration->display_buyer_name,
                    $registration->display_student_name,
                    $registration->user?->email,
                ])->filter()->implode(' ');

                return str_contains(strtolower($haystack), strtolower($search));
            })
            ->when($status !== '', fn (Collection $items) => $items->where('display_status_key', $status))
            ->when($academicYear !== '', fn (Collection $items) => $items->where('display_academic_year', $academicYear))
            ->values();

        $csv = collect([
            ['No. Formulir', 'Nama Pembeli', 'Nama Calon Siswa', 'Tanggal Beli', 'Harga', 'Status Pengisian', 'Tahun Ajaran'],
        ])->concat(
            $rows->map(fn (StudentRegistration $registration) => [
                $registration->display_form_number,
                $registration->display_buyer_name,
                $registration->display_student_name,
                $registration->display_purchase_date,
                $registration->display_price,
                $registration->display_status_label,
                $registration->display_academic_year,
            ])
        )->map(fn (array $columns) => implode(',', array_map(fn ($value) => '"' . str_replace('"', '""', (string) $value) . '"', $columns)))
            ->implode("\n");

        $fileName = 'data-formulir-' . now()->format('Ymd-His') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function decorateFormSale(StudentRegistration $registration): StudentRegistration
    {
        $status = $this->resolveFillingStatus($registration);
        $purchaseDate = $registration->created_at ?? now();

        $registration->display_form_number = $this->resolveFormNumber($registration);
        $registration->display_buyer_name = $registration->user?->name ?? '-';
        $registration->display_student_name = filled($registration->full_name) ? $registration->full_name : '-';
        $registration->display_purchase_date = $purchaseDate->translatedFormat('j M Y');
        $registration->display_academic_year = $this->resolveAcademicYear($purchaseDate);
        $registration->display_price = $this->formatCurrency(self::FORM_PRICE);
        $registration->display_status_key = $status['key'];
        $registration->display_status_label = $status['label'];
        $registration->display_status_tone = $status['tone'];

        return $registration;
    }

    private function resolveFormNumber(StudentRegistration $registration): string
    {
        if (filled($registration->registration_number)) {
            return (string) $registration->registration_number;
        }

        $year = ($registration->created_at ?? now())->format('Y');

        return 'FORM-' . $year . '-' . str_pad((string) $registration->id, 4, '0', STR_PAD_LEFT);
    }

    private function resolveAcademicYear(Carbon $baseDate): string
    {
        $year = (int) $baseDate->format('Y');

        if ((int) $baseDate->format('n') < 7) {
            return ($year - 1) . '/' . $year;
        }

        return $year . '/' . ($year + 1);
    }

    private function resolveFillingStatus(StudentRegistration $registration): array
    {
        if ($registration->submitted_at !== null) {
            return [
                'key' => 'lengkap',
                'label' => 'Diisi Lengkap',
                'tone' => 'emerald',
            ];
        }

        $hasAnyInput = collect([
            $registration->full_name,
            $registration->nickname,
            $registration->birth_place,
            $registration->home_address,
            $registration->father_name,
            $registration->mother_name,
            $registration->child_photo_path,
            $registration->parents_id_card_path,
            $registration->birth_certificate_path,
            $registration->family_card_path,
            $registration->locked_at,
            $registration->registration_number,
        ])->contains(fn ($value) => filled($value));

        if ($hasAnyInput) {
            return [
                'key' => 'belum_lengkap',
                'label' => 'Belum Lengkap',
                'tone' => 'amber',
            ];
        }

        return [
            'key' => 'belum_diisi',
            'label' => 'Belum Diisi',
            'tone' => 'slate',
        ];
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
