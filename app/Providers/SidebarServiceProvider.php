<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Article;
use App\Models\Tag;
class SidebarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Gunakan View Composer untuk file 'sidebar'
        View::composer('layouts.partials.sidebar', function ($view) {
            // Ambil 5 artikel 'published' terbaru (cache selama 10 menit)
            $trendingArticles = Cache::remember('sidebar_trending_articles', now()->addMinutes(10), function () {
                return Article::where('status', 'published')
                    ->latest('published_at')
                    ->take(5)
                    ->get();
            });

            // Ambil 10 Tag terpopuler (berdasarkan jumlah artikel)
            $popularTags = Cache::remember('sidebar_popular_tags', now()->addMinutes(60), function () {
                // 'articles' adalah nama method relasi di Model Tag
                return Tag::withCount('articles')
                    ->orderBy('articles_count', 'desc')
                    ->take(10)
                    ->get();
            });

            // Kirim data ke view
            $view->with([
                'trendingArticles' => $trendingArticles,
                'popularTags' => $popularTags
            ]);
        });
    }
}
