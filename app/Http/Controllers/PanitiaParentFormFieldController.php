<?php

namespace App\Http\Controllers;

use App\Models\ParentFormField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PanitiaParentFormFieldController extends Controller
{
    public function index(): View
    {
        return view('dashboard.panitia.parent-form-fields.index', [
            'fields' => ParentFormField::query()->orderBy('section')->orderBy('sort_order')->orderBy('id')->get(),
            'systemFields' => $this->systemFields(),
        ]);
    }

    public function create(): View
    {
        return view('dashboard.panitia.parent-form-fields.form', [
            'field' => new ParentFormField(),
            'typeOptions' => ParentFormField::TYPES,
            'sectionOptions' => ParentFormField::SECTIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateField($request);
        $validated['field_key'] = ParentFormField::makeKey($validated['label']);

        ParentFormField::create($validated);

        return redirect()
            ->route('panitia.parent-form-fields.index')
            ->with('status', 'Field formulir berhasil ditambahkan.');
    }

    public function edit(ParentFormField $parentFormField): View
    {
        return view('dashboard.panitia.parent-form-fields.form', [
            'field' => $parentFormField,
            'typeOptions' => ParentFormField::TYPES,
            'sectionOptions' => ParentFormField::SECTIONS,
        ]);
    }

    public function update(Request $request, ParentFormField $parentFormField): RedirectResponse
    {
        $parentFormField->update($this->validateField($request));

        return redirect()
            ->route('panitia.parent-form-fields.index')
            ->with('status', 'Field formulir berhasil diperbarui.');
    }

    public function destroy(ParentFormField $parentFormField): RedirectResponse
    {
        $parentFormField->delete();

        return redirect()
            ->route('panitia.parent-form-fields.index')
            ->with('status', 'Field formulir berhasil dihapus.');
    }

    private function validateField(Request $request): array
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_keys(ParentFormField::TYPES))],
            'section' => ['required', Rule::in(array_keys(ParentFormField::SECTIONS))],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'help_text' => ['nullable', 'string', 'max:1000'],
            'options_text' => ['nullable', 'string', 'required_if:type,select'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        $validated['options'] = $validated['type'] === 'select'
            ? collect(preg_split('/\r\n|\r|\n/', (string) $validated['options_text']))
                ->map(fn (string $option) => trim($option))
                ->filter()
                ->unique()
                ->values()
                ->all()
            : null;
        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active');
        unset($validated['options_text']);

        return $validated;
    }

    private function systemFields(): array
    {
        return [
            'Data Anak' => [
                'Nama Lengkap', 'Nama Panggilan', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
                'Asal Daerah', 'Berat Badan', 'Tinggi Badan', 'Kewarganegaraan', 'Agama',
                'Berkebutuhan Khusus', 'Status Anak', 'Golongan Darah', 'Anak ke-', 'Total Anak',
                'Alamat Rumah', 'Riwayat Penyakit',
            ],
            'Data Orang Tua / Wali' => [
                'Identitas Ayah / Wali', 'Pekerjaan Ayah', 'Pendidikan Ayah', 'Penghasilan Ayah',
                'Kontak dan Alamat Ayah', 'Identitas Ibu / Wali', 'Pekerjaan Ibu', 'Pendidikan Ibu',
                'Penghasilan Ibu', 'Kontak dan Alamat Ibu',
            ],
            'Upload Berkas' => [
                'Pas Foto Anak', 'KTP Orang Tua', 'Akta Lahir', 'Kartu Keluarga',
            ],
        ];
    }
}
