<?php
namespace App\Providers;

use CyrildeWit\EloquentViewable\Support\Period;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Article;
use App\Models\Category;
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
            $popularArticles = Article::orderByUniqueViews('desc', Period::pastDays(7))  
                ->where('status', operator: 'published') // Hanya yang published
                ->take(5)
                ->get();
            // Kirim data ke view
            $view->with([
                'trendingArticles' => $trendingArticles,
                'popularTags' => $popularTags,
                'popularArticles' => $popularArticles
            ]);
        });



        View::composer('layouts.public', function ($view) {

            // Kita cache query ini agar tidak membebani database setiap load
            $navigationCategories = Cache::remember('navigation_categories', now()->addHour(), function () {
                return Category::query()
                    ->where('is_featured', true)
                    ->orderBy('nav_order', 'asc')
                    ->get();
            });

            // Kirim variabel $navigationCategories ke 'layouts.public'
            $view->with('navigationCategories', $navigationCategories);
        });
    }
}
