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
            'activityItems' => $this->getContents(SchoolContent::TYPE_ACTIVITY, 4),
            'teacherItems' => $this->getContents(SchoolContent::TYPE_TEACHER, 8),
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
        ]);
    }

    public function teachers(): View
    {
        return view('profile.tenaga-pendidik', [
            'teacherItems' => $this->getContents(SchoolContent::TYPE_TEACHER),
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
}
