<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag; // <-- Gunakan model kustom
use App\Models\User;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml file';

    public function handle()
    {
        $this->info('Generating Sitemap...');
        
        $sitemap = Sitemap::create()
            // 1. Halaman Statis
            ->add(Url::create(route('home'))->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            ->add(Url::create(route('categories.index.all'))->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        // 2. Artikel yang 'published'
        Article::where('status', 'published')->get()->each(function (Article $article) use ($sitemap) {
            $sitemap->add(
                Url::create(route('article.show', [$article->category->slug, $article->slug]))
                    ->setLastModificationDate($article->updated_at)
                    ->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            );
        });

        // 3. Semua Kategori
        Category::all()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(
                Url::create(route('category.show', $category->slug))
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });
        
        // 4. Semua Tag
        Tag::all()->each(function (Tag $tag) use ($sitemap) {
            $sitemap->add(
                Url::create(route('tag.show', $tag->slug))
                    ->setPriority(0.6)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));
        $this->info('Sitemap generated successfully.');
    }
}