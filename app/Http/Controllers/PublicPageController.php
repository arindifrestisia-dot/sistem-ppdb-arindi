<?php

namespace App\Http\Controllers;

use App\Models\SchoolContent;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
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
