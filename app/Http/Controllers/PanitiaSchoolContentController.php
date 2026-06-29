<?php

namespace App\Http\Controllers;

use App\Models\SchoolContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PanitiaSchoolContentController extends Controller
{
    public function index(Request $request): View
    {
        $type = (string) $request->string('type', SchoolContent::TYPE_INFORMATION);

        abort_unless(array_key_exists($type, SchoolContent::allTypeOptions()), 404);

        if ($this->isProfileLogoType($type)) {
            return view('dashboard.panitia.contents.logo-index', [
                'type' => $type,
                'contentItem' => $this->contentsQuery($type)->first(),
            ]);
        }

        if ($this->isGalleryType($type)) {
            return view('dashboard.panitia.contents.gallery-index', [
                'type' => $type,
                'typeOptions' => SchoolContent::typeOptions(),
                'contents' => $this->contentsQuery($type)
                    ->paginate(12)
                    ->withQueryString(),
            ]);
        }

        if ($this->isFacilityType($type)) {
            return view('dashboard.panitia.contents.facility-index', [
                'type' => $type,
                'typeOptions' => SchoolContent::typeOptions(),
                'contents' => $this->contentsQuery($type)
                    ->paginate(12)
                    ->withQueryString(),
            ]);
        }

        if ($this->isActivityType($type)) {
            return view('dashboard.panitia.contents.activity-index', [
                'type' => $type,
                'typeOptions' => SchoolContent::typeOptions(),
                'contents' => $this->contentsQuery($type)
                    ->paginate(12)
                    ->withQueryString(),
            ]);
        }

        if ($this->isTestimonialType($type)) {
            return view('dashboard.panitia.contents.testimonial-index', [
                'type' => $type,
                'contents' => $this->contentsQuery($type)
                    ->paginate(10)
                    ->withQueryString(),
            ]);
        }

        return view('dashboard.panitia.contents.index', [
            'type' => $type,
            'typeOptions' => $this->contentTypeOptionsFor($type),
            'contents' => $this->contentsQuery($type)
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function create(Request $request): View
    {
        $type = (string) $request->string('type', SchoolContent::TYPE_INFORMATION);

        abort_unless(array_key_exists($type, SchoolContent::allTypeOptions()), 404);

        if ($this->isProfileLogoType($type)) {
            return view('dashboard.panitia.contents.logo-form', [
                'contentItem' => new SchoolContent(['type' => $type, 'title' => 'RA Fadhilah', 'is_published' => true]),
            ]);
        }

        if ($this->isGalleryType($type)) {
            return view('dashboard.panitia.contents.gallery-form', [
                'contentItem' => new SchoolContent(['type' => $type, 'is_published' => true]),
            ]);
        }

        if ($this->isFacilityType($type)) {
            return view('dashboard.panitia.contents.facility-form', [
                'contentItem' => new SchoolContent(['type' => $type, 'is_published' => true]),
            ]);
        }

        if ($this->isActivityType($type)) {
            return view('dashboard.panitia.contents.activity-form', [
                'contentItem' => new SchoolContent(['type' => $type, 'is_published' => true]),
            ]);
        }

        if ($this->isTeacherType($type)) {
            return view('dashboard.panitia.contents.teacher-form', [
                'contentItem' => new SchoolContent(['type' => $type, 'is_published' => true]),
            ]);
        }

        if ($this->isTestimonialType($type)) {
            return view('dashboard.panitia.contents.testimonial-form', [
                'contentItem' => new SchoolContent(['type' => $type, 'is_published' => true]),
            ]);
        }

        return view('dashboard.panitia.contents.form', [
            'contentItem' => new SchoolContent(['type' => $type, 'is_published' => true]),
            'typeOptions' => $this->contentTypeOptionsFor($type),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $type = (string) $request->input('type', SchoolContent::TYPE_INFORMATION);

        if ($this->isProfileLogoType($type)) {
            $validated = $this->validateProfileLogoRequest($request);

            $content = DB::transaction(function () use ($request, $validated) {
                $content = new SchoolContent($validated);
                $content->save();

                $this->storeImages($request, $content);

                return $content;
            });

            return redirect()
                ->route('panitia.contents.index', ['type' => $content->type])
                ->with('status', 'Logo RA Fadhilah berhasil ditambahkan.');
        }

        if ($this->isGalleryType($type)) {
            $validated = $this->validateGalleryRequest($request);

            DB::transaction(function () use ($request, $validated) {
                $baseSortOrder = max(
                    (int) SchoolContent::where('type', SchoolContent::TYPE_GALLERY)->max('sort_order'),
                    max(((int) $validated['sort_order']) - 1, 0)
                );

                foreach ($request->file('images', []) as $index => $image) {
                    $content = new SchoolContent([
                        'type' => SchoolContent::TYPE_GALLERY,
                        'title' => 'Galeri Sekolah',
                        'excerpt' => null,
                        'content' => null,
                        'published_at' => $validated['published_at'] ?? now()->toDateString(),
                        'sort_order' => $baseSortOrder + $index + 1,
                        'is_published' => $validated['is_published'],
                    ]);
                    $content->save();

                    $content->images()->create([
                        'image_path' => $image->store('school-contents', 'public'),
                        'sort_order' => 0,
                    ]);

                    $this->syncCoverImage($content->fresh('images'));
                }
            });

            return redirect()
                ->route('panitia.contents.index', ['type' => SchoolContent::TYPE_GALLERY])
                ->with('status', 'Foto galeri berhasil ditambahkan.');
        }

        if ($this->isFacilityType($type)) {
            $validated = $this->validateFacilityRequest($request);

            $content = DB::transaction(function () use ($request, $validated) {
                $content = new SchoolContent($validated);
                $content->save();

                $this->storeImages($request, $content);

                return $content;
            });

            return redirect()
                ->route('panitia.contents.index', ['type' => $content->type])
                ->with('status', 'Fasilitas berhasil ditambahkan.');
        }

        if ($this->isActivityType($type)) {
            $validated = $this->validateActivityRequest($request);

            $content = DB::transaction(function () use ($request, $validated) {
                $content = new SchoolContent($validated);
                $content->save();

                $this->storeImages($request, $content);

                return $content;
            });

            return redirect()
                ->route('panitia.contents.index', ['type' => $content->type])
                ->with('status', 'Kegiatan berhasil ditambahkan.');
        }

        if ($this->isTeacherType($type)) {
            $validated = $this->validateTeacherRequest($request);

            $content = DB::transaction(function () use ($request, $validated) {
                $content = new SchoolContent($validated);
                $content->save();

                $this->storeImages($request, $content);

                return $content;
            });

            return redirect()
                ->route('panitia.contents.index', ['type' => $content->type])
                ->with('status', 'Tenaga pendidik berhasil ditambahkan.');
        }

        if ($this->isTestimonialType($type)) {
            $validated = $this->validateTestimonialRequest($request);

            $content = DB::transaction(function () use ($request, $validated) {
                $content = new SchoolContent($validated);
                $content->save();

                $this->storeImages($request, $content);

                return $content;
            });

            return redirect()
                ->route('panitia.contents.index', ['type' => $content->type])
                ->with('status', 'Testimoni berhasil ditambahkan.');
        }

        $validated = $this->validateRequest($request);
        $content = DB::transaction(function () use ($request, $validated) {
            $content = new SchoolContent($validated);
            $content->save();

            $this->storeImages($request, $content);

            return $content;
        });

        return redirect()
            ->route('panitia.contents.index', ['type' => $content->type])
            ->with('status', 'Konten sekolah berhasil ditambahkan.');
    }

    public function edit(SchoolContent $content): View|RedirectResponse
    {
        $this->ensureLegacyImageTracked($content);
        $content->load('images');

        if ($this->isProfileLogoType($content->type)) {
            return view('dashboard.panitia.contents.logo-form', [
                'contentItem' => $content,
            ]);
        }

        if ($this->isGalleryType($content->type)) {
            return redirect()
                ->route('panitia.contents.index', ['type' => $content->type])
                ->with('status', 'Untuk galeri, gunakan menu upload dan hapus foto.');
        }

        if ($this->isFacilityType($content->type)) {
            return view('dashboard.panitia.contents.facility-form', [
                'contentItem' => $content,
            ]);
        }

        if ($this->isActivityType($content->type)) {
            return view('dashboard.panitia.contents.activity-form', [
                'contentItem' => $content,
            ]);
        }

        if ($this->isTeacherType($content->type)) {
            return view('dashboard.panitia.contents.teacher-form', [
                'contentItem' => $content,
            ]);
        }

        if ($this->isTestimonialType($content->type)) {
            return view('dashboard.panitia.contents.testimonial-form', [
                'contentItem' => $content,
            ]);
        }

        return view('dashboard.panitia.contents.form', [
            'contentItem' => $content,
            'typeOptions' => $this->contentTypeOptionsFor($content->type),
        ]);
    }

    public function update(Request $request, SchoolContent $content): RedirectResponse
    {
        $this->ensureLegacyImageTracked($content);

        if ($this->isProfileLogoType($content->type)) {
            $validated = $this->validateProfileLogoRequest($request, true);

            DB::transaction(function () use ($request, $validated, $content) {
                $content->fill($validated);
                $content->save();

                if ($request->hasFile('images')) {
                    $this->removeAllImages($content);
                }

                $this->storeImages($request, $content);
                $this->syncCoverImage($content->fresh('images'));
            });

            return redirect()
                ->route('panitia.contents.index', ['type' => $content->type])
                ->with('status', 'Logo RA Fadhilah berhasil diperbarui.');
        }

        if ($this->isGalleryType($content->type)) {
            return redirect()
                ->route('panitia.contents.index', ['type' => $content->type])
                ->with('status', 'Untuk galeri, gunakan menu upload dan hapus foto.');
        }

        if ($this->isFacilityType($content->type)) {
            $validated = $this->validateFacilityRequest($request, true);

            DB::transaction(function () use ($request, $validated, $content) {
                $content->fill($validated);
                $content->save();

                $this->removeSelectedImages($request, $content);
                $this->storeImages($request, $content);
                $this->syncCoverImage($content->fresh('images'));
            });

            return redirect()
                ->route('panitia.contents.index', ['type' => $content->type])
                ->with('status', 'Fasilitas berhasil diperbarui.');
        }

        if ($this->isActivityType($content->type)) {
            $validated = $this->validateActivityRequest($request, true);

            DB::transaction(function () use ($request, $validated, $content) {
                $content->fill($validated);
                $content->save();

                if ($request->hasFile('images')) {
                    $this->removeAllImages($content);
                }

                $this->storeImages($request, $content);
                $this->syncCoverImage($content->fresh('images'));
            });

            return redirect()
                ->route('panitia.contents.index', ['type' => $content->type])
                ->with('status', 'Kegiatan berhasil diperbarui.');
        }

        if ($this->isTeacherType($content->type)) {
            $validated = $this->validateTeacherRequest($request, true);

            DB::transaction(function () use ($request, $validated, $content) {
                $content->fill($validated);
                $content->save();

                if ($request->hasFile('images')) {
                    $this->removeAllImages($content);
                } else {
                    $this->removeSelectedImages($request, $content);
                }

                $this->storeImages($request, $content);
                $this->syncCoverImage($content->fresh('images'));
            });

            return redirect()
                ->route('panitia.contents.index', ['type' => $content->type])
                ->with('status', 'Tenaga pendidik berhasil diperbarui.');
        }

        if ($this->isTestimonialType($content->type)) {
            $validated = $this->validateTestimonialRequest($request, true);

            DB::transaction(function () use ($request, $validated, $content) {
                $content->fill($validated);
                $content->save();

                if ($request->hasFile('images')) {
                    $this->removeAllImages($content);
                }

                $this->storeImages($request, $content);
                $this->syncCoverImage($content->fresh('images'));
            });

            return redirect()
                ->route('panitia.contents.index', ['type' => $content->type])
                ->with('status', 'Testimoni berhasil diperbarui.');
        }

        $validated = $this->validateRequest($request);

        DB::transaction(function () use ($request, $validated, $content) {
            $content->fill($validated);
            $content->save();

            $this->removeSelectedImages($request, $content);
            $this->storeImages($request, $content);
            $this->syncCoverImage($content->fresh('images'));
        });

        return redirect()
            ->route('panitia.contents.index', ['type' => $content->type])
            ->with('status', 'Konten sekolah berhasil diperbarui.');
    }

    public function destroy(SchoolContent $content): RedirectResponse
    {
        $type = $content->type;

        $this->ensureLegacyImageTracked($content);
        $content->load('images');

        foreach ($content->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        if ($content->image_path && $content->images->doesntContain(fn ($image) => $image->image_path === $content->image_path)) {
            Storage::disk('public')->delete($content->image_path);
        }

        $content->delete();

        return redirect()
            ->route('panitia.contents.index', ['type' => $type])
            ->with('status', 'Konten sekolah berhasil dihapus.');
    }

    protected function validateRequest(Request $request): array
    {
        return $request->validate([
            'type' => ['required', 'in:' . implode(',', array_keys(SchoolContent::allTypeOptions()))],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
        ]) + [
            'is_published' => $request->boolean('is_published'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }

    protected function validateGalleryRequest(Request $request): array
    {
        return $request->validate([
            'type' => ['required', 'in:' . SchoolContent::TYPE_GALLERY],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'max:4096'],
        ]) + [
            'is_published' => $request->boolean('is_published', true),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }

    protected function validateProfileLogoRequest(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'type' => ['required', 'in:' . SchoolContent::TYPE_PROFILE_LOGO],
            'images' => [$isUpdate ? 'nullable' : 'required', 'array', 'max:1'],
            'images.*' => ['image', 'max:4096'],
        ]) + [
            'title' => 'RA Fadhilah',
            'excerpt' => null,
            'content' => null,
            'published_at' => now()->toDateString(),
            'is_published' => true,
            'sort_order' => 0,
        ];
    }

    protected function validateFacilityRequest(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'type' => ['required', 'in:' . SchoolContent::TYPE_FACILITY],
            'title' => ['required', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
            'images' => [$isUpdate ? 'nullable' : 'required', 'array', 'max:1'],
            'images.*' => ['image', 'max:4096'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
        ]) + [
            'excerpt' => null,
            'content' => null,
            'is_published' => $request->boolean('is_published', true),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }

    protected function validateActivityRequest(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'type' => ['required', 'in:' . SchoolContent::TYPE_ACTIVITY],
            'title' => ['required', 'string', 'max:255'],
            'images' => [$isUpdate ? 'nullable' : 'required', 'array', 'max:1'],
            'images.*' => ['image', 'max:4096'],
        ]) + [
            'excerpt' => null,
            'content' => null,
            'published_at' => now()->toDateString(),
            'is_published' => true,
            'sort_order' => 0,
        ];
    }

    protected function validateTeacherRequest(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'type' => ['required', 'in:' . SchoolContent::TYPE_TEACHER],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:100'],
            'images' => [$isUpdate ? 'nullable' : 'required', 'array', 'max:1'],
            'images.*' => ['image', 'max:4096'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
        ]) + [
            'published_at' => now()->toDateString(),
            'is_published' => true,
            'sort_order' => 0,
        ];
    }

    protected function validateTestimonialRequest(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'type' => ['required', 'in:' . SchoolContent::TYPE_TESTIMONIAL],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
            'images' => [$isUpdate ? 'nullable' : 'required', 'array', 'max:1'],
            'images.*' => ['image', 'max:4096'],
        ]) + [
            'excerpt' => null,
            'published_at' => now()->toDateString(),
            'is_published' => $request->boolean('is_published'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }

    protected function storeImages(Request $request, SchoolContent $content): void
    {
        if (! $request->hasFile('images')) {
            $this->syncCoverImage($content->fresh('images'));
            return;
        }

        $startOrder = (int) $content->images()->max('sort_order') + 1;

        foreach ($request->file('images') as $index => $image) {
            $content->images()->create([
                'image_path' => $image->store('school-contents', 'public'),
                'sort_order' => $startOrder + $index,
            ]);
        }

        $this->syncCoverImage($content->fresh('images'));
    }

    protected function removeSelectedImages(Request $request, SchoolContent $content): void
    {
        $removeIds = collect($request->input('remove_images', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        if ($removeIds->isEmpty()) {
            return;
        }

        $images = $content->images()
            ->whereIn('id', $removeIds)
            ->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
    }

    protected function removeAllImages(SchoolContent $content): void
    {
        $content->loadMissing('images');

        foreach ($content->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        if ($content->image_path) {
            Storage::disk('public')->delete($content->image_path);
            $content->forceFill(['image_path' => null])->save();
        }
    }

    protected function syncCoverImage(SchoolContent $content): void
    {
        $coverImage = $content->images->first()?->image_path;

        if ($content->image_path === $coverImage) {
            return;
        }

        $content->forceFill([
            'image_path' => $coverImage,
        ])->save();
    }

    protected function ensureLegacyImageTracked(SchoolContent $content): void
    {
        if (! $content->image_path || $content->images()->exists()) {
            return;
        }

        $content->images()->create([
            'image_path' => $content->image_path,
            'sort_order' => 0,
        ]);
    }

    protected function contentsQuery(string $type)
    {
        return SchoolContent::with('images')
            ->where('type', $type)
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }

    protected function contentTypeOptionsFor(string $type): array
    {
        return array_key_exists($type, SchoolContent::profileTypeOptions())
            ? SchoolContent::profileTypeOptions()
            : SchoolContent::typeOptions();
    }

    protected function isGalleryType(string $type): bool
    {
        return $type === SchoolContent::TYPE_GALLERY;
    }

    protected function isProfileLogoType(string $type): bool
    {
        return $type === SchoolContent::TYPE_PROFILE_LOGO;
    }

    protected function isFacilityType(string $type): bool
    {
        return $type === SchoolContent::TYPE_FACILITY;
    }

    protected function isActivityType(string $type): bool
    {
        return $type === SchoolContent::TYPE_ACTIVITY;
    }

    protected function isTeacherType(string $type): bool
    {
        return $type === SchoolContent::TYPE_TEACHER;
    }

    protected function isTestimonialType(string $type): bool
    {
        return $type === SchoolContent::TYPE_TESTIMONIAL;
    }
}
