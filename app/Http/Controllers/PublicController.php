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
        // 1. Atur SEO (Sudah ada)
        SEOMeta::setTitle('Portal Berita Terkini dan Terpercaya');

        // 2. Ambil 15 artikel TERBARU (untuk 3 blok layout)
        $latestArticles = Article::with('user', 'category')
            ->where('status', 'published')
            ->latest('published_at')
            ->take(15) // 1 (Hero) + 4 (Slider) + 10 (Daftar)
            ->get();

        // 3. Bagi artikel TERBARU
        $heroArticle = $latestArticles->shift(); // Ambil 1 artikel pertama untuk Hero
        $topGridArticles = $latestArticles->splice(0, 4); // Ambil 4 berikutnya untuk Slider
        $latestListArticles = $latestArticles; // Sisanya (10) untuk Daftar Umpan

        // 4. Ambil 5 artikel TERPOPULER (untuk blok trending teks)
        $popularArticles = Article::orderByUniqueViews('desc', Period::pastDays(7))
            ->where('status', 'published')
            ->take(5)
            ->get();

        // 5. Kirim SEMUA 4 set data ke view
        return view('home', [
            'heroArticle' => $heroArticle,
            'popularArticles' => $popularArticles,  // <-- Data baru
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
            'query' => $query // Kirim query asli untuk ditampilkan
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

        // Ambil SEMUA kategori, urutkan berdasarkan nama
        $allCategories = Category::orderBy('name', 'asc')->get();
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
