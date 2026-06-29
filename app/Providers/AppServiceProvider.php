<?php

namespace App\Providers;

use App\Models\SchoolContent;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $profile = collect();

            if (Schema::hasTable('school_contents')) {
                $profile = SchoolContent::query()
                    ->whereIn('type', [
                        SchoolContent::TYPE_PROFILE_LOGO,
                        SchoolContent::TYPE_PROFILE_NAME,
                        SchoolContent::TYPE_PROFILE_CONTACT,
                    ])
                    ->published()
                    ->orderBy('sort_order')
                    ->orderByDesc('published_at')
                    ->orderByDesc('id')
                    ->get()
                    ->groupBy('type')
                    ->map(fn ($items) => $items->first());
            }

            $view->with('publicSchoolProfile', $profile);
        });
    }
}
