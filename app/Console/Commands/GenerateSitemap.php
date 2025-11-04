<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;

class GenerateSitemap extends Command
{
    // Tanda tangan (nama) command kita
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml file';

    public function handle()
    {
        $this->info('Generating Sitemap...');
        $sitemap = Sitemap::create();

        // 1. Halaman Statis (Home)
        $sitemap->add(
            Url::create('/')
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        // 2. Semua Artikel yang 'published'
        Article::where('status', 'published')->get()->each(function (Article $article) use ($sitemap) {
            $sitemap->add(
                Url::create(route('article.show', [$article->category->slug, $article->slug]))
                    ->setLastModificationDate($article->updated_at)
                    ->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY) // Berita cepat berubah
            );
        });

        // 3. Semua Kategori
        Category::all()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(
                Url::create(route('category.show', $category->slug))
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        // (Lakukan hal yang sama untuk Tag dan Author jika perlu)

        // Simpan file ke public path
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}