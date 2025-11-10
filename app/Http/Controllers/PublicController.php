<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use App\Models\Tag;
use App\Models\User;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use CyrildeWit\EloquentViewable\Support\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    /**
     * Menampilkan halaman Home (Beranda).
     */
    public function home()
    {
        SEOMeta::setTitle('Portal Berita Terkini dan Terpercaya');

        // Kumpulan ID untuk dikecualikan agar tidak duplikat
        $excludeIds = [];

        // ===========================================
        // 1. LOGIKA BLOK HERO (KONTROL EDITOR)
        // ===========================================
        // Coba cari artikel yang di-pin oleh Editor
        $heroArticle = Article::with('user', 'category')
            ->where('status', 'published')
            ->where('is_hero_pinned', true)
            ->latest('published_at') // Ambil yang di-pin terbaru
            ->first();

        // JIKA TIDAK ADA YANG DI-PIN, ambil saja yang terbaru
        if (!$heroArticle) {
            $heroArticle = Article::with('user', 'category')
                ->where('status', 'published')
                ->latest('published_at')
                ->first();
        }

        // Tambahkan ID Hero ke daftar pengecualian
        if ($heroArticle) {
            $excludeIds[] = $heroArticle->id;
        }

        // ===========================================
        // 2. LOGIKA BLOK POPULER (TETAP OTOMATIS)
        // ===========================================
        $popularArticles = Article::orderByUniqueViews('desc', Period::pastDays(7))
            ->where('status', 'published')
            ->whereNotIn('id', $excludeIds) // Kecualikan hero
            ->take(5)
            ->get();

        // ===========================================
        // 3. LOGIKA BLOK SLIDER (KONTROL EDITOR)
        // ===========================================
        $topGridArticles = Article::with('user', 'category')
            ->where('status', 'published')
            ->where('is_editors_pick', true) // <-- Ambil Pilihan Editor
            ->whereNotIn('id', $excludeIds) // Kecualikan hero
            ->latest('published_at')
            ->take(4) // Ambil 4
            ->get();

        // Tambahkan ID Pilihan Editor ke daftar pengecualian
        $excludeIds = array_merge($excludeIds, $topGridArticles->pluck('id')->toArray());

        // ===========================================
        // 4. LOGIKA BLOK UMPAN (FEED)
        // ===========================================
        $latestListArticles = Article::with('user', 'category')
            ->where('status', 'published')
            ->whereNotIn('id', $excludeIds) // Kecualikan SEMUA yang sudah tampil
            ->latest('published_at')
            ->take(10) // Sisanya 10
            ->get();

        // 5. Kirim semua 4 set data ke view
        return view('home', [
            'heroArticle' => $heroArticle,
            'popularArticles' => $popularArticles,
            'topGridArticles' => $topGridArticles,
            'latestListArticles' => $latestListArticles,
        ]);
    }
    /**
     * Menampilkan satu halaman artikel penuh.
     * * Berkat Route Model Binding, Laravel otomatis
     * mencari Category dan Article berdasarkan slug.
     */
    public function articleShow(Category $category, Article $article)
    {
        views($article)->record();

        // Pastikan artikel yang diakses sudah 'published'
        // (Atau admin yang sedang login)
        if ($article->status !== 'published' && !auth()->check()) {
            abort(404);
        }


        SEOMeta::setTitle($article->title);
        SEOMeta::setDescription($article->excerpt);
        SEOMeta::setCanonical(route('article.show', [$category->slug, $article->slug]));

        OpenGraph::setTitle($article->title)
            ->setDescription($article->excerpt)
            ->setType('article')
            ->setArticle([
                'published_time' => $article->published_at->toIso8601String(),
                'author' => $article->user->name,
            ]);

        // PERBAIKAN: Hanya tambahkan gambar jika ada
        if ($article->hasMedia('featured')) {
            OpenGraph::addImage($article->getFirstMediaUrl('featured', 'featured-large'));
        }

        TwitterCard::setTitle($article->title);

        JsonLd::setType('Article')
            ->setTitle($article->title)
            ->setDescription($article->excerpt)
            ->setImages($article->hasMedia('featured') ? [$article->getFirstMediaUrl('featured', 'featured-large')] : []);
        $relatedArticles = Article::where('category_id', $article->category_id)
            ->where('status', 'published')  // Hanya yang sudah terbit
            ->where('id', '!=', $article->id) // <-- PENTING: Kecualikan artikel ini
            ->latest('published_at') // Ambil yang terbaru
            ->take(3) // Batasi 3 artikel
            ->get();
        // Kirim data artikel ke view

        return view('article.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles
        ]);
    }


    /**
     * Menampilkan halaman arsip kategori.
     */
    public function categoryShow(Category $category)
    {
        SEOMeta::setTitle("Kategori: " . $category->name);
        SEOMeta::setDescription("Arsip berita terbaru untuk kategori {$category->name} di LionNews.");
        // Ambil artikel milik kategori ini
        // Filter hanya yang 'published'
        // Urutkan (terbaru dulu)
        // Dan Paginasi (tampilkan 12 per halaman)
        $articles = $category->articles()
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(12);

        // Kirim data kategori dan artikelnya ke view
        return view('category.show', [
            'category' => $category,
            'articles' => $articles
        ]);
    }

    /**
     * Menampilkan halaman hasil pencarian.
     */
    public function search(Request $request)
    {
        // 1. Ambil kata kunci pencarian dari URL ( ?q=... )
        $query = $request->input('q');

        // 2. Lakukan pencarian jika $query tidak kosong
        if (empty($query)) {
            // Jika kosong, kembalikan ke home atau tampilkan pesan
            return redirect()->route('home');
        }

        // 3. Query ke database
        $articles = Article::with('user', 'category') // Eager loading
            ->where('status', 'published') // Hanya cari yang sudah terbit
            ->where(function ($dbQuery) use ($query) {
                // Cari di 'title' ATAU 'body'
                $dbQuery->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('body', 'LIKE', "%{$query}%");
            })
            ->latest('published_at') // Tampilkan yang terbaru dulu
            ->paginate(10); // Paginasi hasil

        // 4. Kirim hasil ke view
        return view('search.results', [
            'articles' => $articles,
            'query' => $query
        ]);
    }

    /**
     * Menampilkan halaman arsip penulis.
     */
    public function authorShow(User $user)
    {
        SEOMeta::setTitle("Artikel oleh: " . $user->name);
        SEOMeta::setDescription("Kumpulan artikel yang ditulis oleh {$user->name} di LionNews.");
        // Kita hanya ingin menampilkan jurnalis/editor, bukan admin
        if ($user->role === 'admin') {
            // (Atau bisa juga dilempar ke 404)
            // return redirect()->route('home');
        }

        // Ambil semua artikel milik $user
        // Filter hanya yang 'published'
        // Urutkan (terbaru dulu) dan paginasi
        $articles = $user->articles()
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(12); // Tampilkan 12 per halaman

        // Kirim data user dan artikelnya ke view
        return view('author.show', [
            'author' => $user, // Kita ganti nama jadi 'author' agar lebih jelas di view
            'articles' => $articles
        ]);
    }


    /**
     * Menampilkan halaman arsip tag.
     */
    public function tagShow(Tag $tag) // <-- Route Model Binding by Slug
    {
        SEOMeta::setTitle("Tag: " . $tag->name);
        SEOMeta::setDescription("Arsip berita terbaru untuk tag {$tag->name} di LionNews.");
        // Ambil semua artikel yang memiliki $tag ini
        // (Relasi 'articles' sudah kita buat di Fase 3)
        // Filter hanya yang 'published'
        // Urutkan (terbaru dulu) dan paginasi
        $articles = $tag->articles()
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(12);

        // Kirim data tag dan artikelnya ke view
        return view('tag.show', [
            'tag' => $tag,
            'articles' => $articles
        ]);
    }

    public function allCategories()
    {
        SEOMeta::setTitle('Semua Kategori Berita');
        $allCategories = Category::query()
            // Filter: Hanya tampilkan kategori yang...
            ->where(function ($query) {
                // 1. Memiliki artikel (yang sudah publish)
                $query->whereHas('articles', function ($subQuery) {
                    $subQuery->where('status', Article::STATUS_PUBLISHED);
                })
                    // 2. ATAU anak-anaknya memiliki artikel (jika ini Kategori Induk)
                    ->orWhereHas('children.articles', function ($subQuery) {
                    $subQuery->where('status', Article::STATUS_PUBLISHED);
                });
            })
            ->orderBy('name', 'asc')
            ->get();

        // Tampilan 'category.index-all' Anda tidak perlu diubah
        return view('category.index-all', [
            'categories' => $allCategories
        ]);
    }

    public function pageShow(Page $page)
    {
        // Cek jika halaman sudah di-publish
        if (!$page->is_published) {
            abort(404);
        }

        // Atur SEO
        SEOMeta::setTitle($page->title);
        SEOMeta::setDescription(Str::limit(strip_tags($page->body), 155));

        // Gunakan satu view 'show' generik
        return view('pages.show', compact('page'));
    }
}
