<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Menampilkan halaman Home (Beranda).
     */
    public function home()
    {
        // Ambil 10 artikel terbaru
        // 1. Yang statusnya 'published'
        // 2. Diurutkan berdasarkan 'published_at' (terbaru dulu)
        // 3. 'with' untuk Eager Loading (efisiensi query)
        $articles = Article::with('user', 'category')
            ->where('status', 'published')
            ->latest('published_at')
            ->take(9) // Ambil 9 untuk grid 3x3
            ->get();

        // Kirim data $articles ke view 'home'
        return view('home', [
            'articles' => $articles
        ]);
    }


    /**
     * Menampilkan satu halaman artikel penuh.
     * * Berkat Route Model Binding, Laravel otomatis
     * mencari Category dan Article berdasarkan slug.
     */
    public function articleShow(Category $category, Article $article)
    {
        // Pastikan artikel yang diakses sudah 'published'
        // (Atau admin yang sedang login)
        if ($article->status !== 'published' && !auth()->check()) {
            abort(404);
        }

        // Kirim data artikel ke view
        return view('article.show', [
            'article' => $article
        ]);
    }


    /**
     * Menampilkan halaman arsip kategori.
     */
    public function categoryShow(Category $category)
    {
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
}
