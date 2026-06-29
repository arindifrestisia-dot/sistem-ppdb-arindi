<?php

namespace App\Http\Controllers;

use App\Models\SchoolContent;
use App\Models\HomeBanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'homeBanners' => Schema::hasTable('home_banners')
                ? HomeBanner::query()->get()->keyBy('slot')
                : collect(),
            'informationItems' => $this->getContents(SchoolContent::TYPE_INFORMATION, 6),
            'achievementItems' => $this->getContents(SchoolContent::TYPE_ACHIEVEMENT, 3),
            'galleryItems' => $this->getContents(SchoolContent::TYPE_GALLERY, 8),
            'facilityItems' => $this->getContents(SchoolContent::TYPE_FACILITY, 4),
            'activityItems' => $this->getContents(SchoolContent::TYPE_ACTIVITY),
            'teacherItems' => $this->getContents(SchoolContent::TYPE_TEACHER, 8),
            'testimonialItems' => $this->getTestimonials(),
        ]);
    }

    public function facilities(): View
    {
        return view('public.facilities', [
            'facilityItems' => $this->getContents(SchoolContent::TYPE_FACILITY),
        ]);
    }

    public function greeting(): View
    {
        return $this->profileView(
            SchoolContent::TYPE_PROFILE_GREETING,
            'profile.katasambutan',
            'Kata Sambutan',
            'Sambutan RA Fadhilah'
        );
    }

    public function vision(): View
    {
        return $this->profileView(
            SchoolContent::TYPE_PROFILE_VISION,
            'profile.visi-misi',
            'Visi Misi & Strategi Pembelajaran',
            'Visi, Misi, dan Strategi Pembelajaran RA Fadhilah'
        );
    }

    public function history(): View
    {
        return $this->profileView(
            SchoolContent::TYPE_PROFILE_HISTORY,
            'profile.sejarah',
            'Sejarah',
            'Perjalanan RA Fadhilah'
        );
    }

    public function profileProgram(): View
    {
        return $this->profileView(
            SchoolContent::TYPE_PROFILE_PROGRAM,
            'profile.program-kegiatan-ra',
            'Program Kegiatan',
            'Program Kegiatan RA Fadhilah'
        );
    }

    public function contact(): View
    {
        return $this->profileView(
            SchoolContent::TYPE_PROFILE_CONTACT,
            'profile.kontak-kami',
            'Kontak Kami',
            'Kontak RA Fadhilah'
        );
    }

    public function achievements(): View
    {
        return view('public.achievements', [
            'achievementItems' => $this->getContents(SchoolContent::TYPE_ACHIEVEMENT),
        ]);
    }

    public function showAchievement(SchoolContent $content): View
    {
        abort_unless(
            $content->type === SchoolContent::TYPE_ACHIEVEMENT && $content->is_published,
            404
        );

        $content->load('images');

        $relatedAchievements = Schema::hasTable('school_contents')
            ? SchoolContent::query()
                ->where('type', SchoolContent::TYPE_ACHIEVEMENT)
                ->published()
                ->whereKeyNot($content->getKey())
                ->orderByDesc('published_at')
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->limit(3)
                ->get()
            : collect();

        return view('blog.prestasi-show', [
            'achievementItem' => $content,
            'relatedAchievements' => $relatedAchievements,
            'recentPosts' => $this->getRecentPosts($content),
        ]);
    }

    public function activities(): View
    {
        return view('public.activities', [
            'activityItems' => $this->getContents(SchoolContent::TYPE_ACTIVITY),
        ]);
    }

    public function storeTestimonial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'testimonial' => ['required', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'max:4096'],
            'website' => ['nullable', 'prohibited'],
        ]);

        $imagePath = $request->hasFile('photo')
            ? $request->file('photo')->store('school-contents', 'public')
            : null;

        $testimonial = SchoolContent::create([
            'type' => SchoolContent::TYPE_TESTIMONIAL,
            'title' => $validated['name'],
            'excerpt' => null,
            'content' => $validated['testimonial'],
            'image_path' => $imagePath,
            'published_at' => now()->toDateString(),
            'is_published' => true,
            'sort_order' => 0,
        ]);

        if ($imagePath) {
            $testimonial->images()->create([
                'image_path' => $imagePath,
                'sort_order' => 0,
            ]);
        }

        return redirect(route('home') . '#testimoni')
            ->with('testimonial_status', 'Terima kasih, testimoni Bunda/Ayah sudah tampil di website.');
    }

    public function news(): View
    {
        return view('blog.berita', [
            'newsItems' => $this->getContents(SchoolContent::TYPE_INFORMATION),
        ]);
    }

    public function showNews(SchoolContent $content): View
    {
        abort_unless(
            $content->type === SchoolContent::TYPE_INFORMATION && $content->is_published,
            404
        );

        $content->load('images');

        $relatedNews = Schema::hasTable('school_contents')
            ? SchoolContent::query()
                ->where('type', SchoolContent::TYPE_INFORMATION)
                ->published()
                ->whereKeyNot($content->getKey())
                ->orderByDesc('published_at')
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->limit(3)
                ->get()
            : collect();

        return view('blog.show', [
            'newsItem' => $content,
            'relatedNews' => $relatedNews,
            'recentPosts' => $this->getRecentPosts($content),
        ]);
    }

    public function teachers(): View
    {
        return view('profile.tenaga-pendidik', [
            'teacherItems' => $this->getContents(SchoolContent::TYPE_TEACHER),
        ]);
    }

    public function showTeacher(SchoolContent $teacher): View
    {
        abort_unless(
            $teacher->type === SchoolContent::TYPE_TEACHER && $teacher->is_published,
            404
        );

        return view('profile.tenaga-pendidik-detail', [
            'teacher' => $teacher,
        ]);
    }

    protected function getContents(string $type, ?int $limit = null)
    {
        if (! Schema::hasTable('school_contents')) {
            return collect();
        }

        $query = SchoolContent::query()
            ->where('type', $type)
            ->published()
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->orderByDesc('id');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    protected function profileView(string $type, string $fallbackView, string $label, string $fallbackTitle): View
    {
        $content = $this->getProfileContent($type);

        if (! $content) {
            return view($fallbackView);
        }

        return view('profile.dynamic', [
            'profileContent' => $content,
            'profileLabel' => $label,
            'profileTitle' => $content->title ?: $fallbackTitle,
        ]);
    }

    protected function getProfileContent(string $type): ?SchoolContent
    {
        if (! Schema::hasTable('school_contents')) {
            return null;
        }

        return SchoolContent::query()
            ->where('type', $type)
            ->published()
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->first();
    }

    protected function getTestimonials()
    {
        if (! Schema::hasTable('school_contents')) {
            return collect();
        }

        return SchoolContent::query()
            ->where('type', SchoolContent::TYPE_TESTIMONIAL)
            ->published()
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get();
    }

    protected function getRecentPosts(SchoolContent $currentContent, int $limit = 5)
    {
        if (! Schema::hasTable('school_contents')) {
            return collect();
        }

        return SchoolContent::query()
            ->whereIn('type', [
                SchoolContent::TYPE_INFORMATION,
                SchoolContent::TYPE_ACHIEVEMENT,
            ])
            ->published()
            ->whereKeyNot($currentContent->getKey())
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }
}
