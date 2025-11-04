<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil artikel, urutkan dari yang terbaru
        // 'with' (Eager Loading) untuk mengambil relasi 'user' dan 'category'
        // Ini mencegah N+1 query problem (sangat efisien)
        $articles = Article::with('user', 'category')
            ->latest() // Urutkan berdasarkan created_at terbaru
            ->paginate(15); // Tampilkan 15 artikel per halaman

        return view('admin.articles.index', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua kategori untuk dropdown
        $categories = Category::orderBy('name')->get();

        // Tampilkan view 'create' dan kirim data $categories
        return view('admin.articles.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:articles',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'required|string',
            'body' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // maks 2MB
        ]);

        // 2. Handle upload gambar
        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            // Simpan gambar di 'storage/app/public/articles'
            // Buat folder 'articles' jika belum ada
            $imagePath = $request->file('featured_image')->store('articles', 'public');
        }

        // 3. Buat slug dan tambahkan data yang divalidasi
        Article::create([
            'title' => $validatedData['title'],
            'category_id' => $validatedData['category_id'],
            'excerpt' => $validatedData['excerpt'],
            'body' => $validatedData['body'],
            'featured_image_path' => $imagePath,
            'user_id' => Auth::id(), // Ambil ID user yang sedang login
            'slug' => Str::slug($validatedData['title']), // Buat slug otomatis
            'status' => 'draft', // Set status default
            'published_at' => now(), // Set waktu publish (atau null jika ingin manual)
        ]);

        // 4. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel baru berhasil disimpan sebagai draft.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article) // <-- Route Model Binding
    {
        // Cek Otorisasi (Contoh: Jurnalis hanya boleh edit artikelnya sendiri)
        // $this->authorize('update', $article); // Kita akan buat Policy ini nanti

        $categories = Category::orderBy('name')->get();

        return view('admin.articles.edit', [
            'article' => $article,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            // Pastikan 'unique' mengabaikan artikel ini sendiri
            'title' => 'required|string|max:255|unique:articles,title,' . $article->id,
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'required|string',
            'body' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Handle upload gambar (jika ada gambar baru)
        if ($request->hasFile('featured_image')) {
            // Hapus gambar lama jika ada
            if ($article->featured_image_path) {
                Storage::disk('public')->delete($article->featured_image_path);
            }
            // Simpan gambar baru
            $imagePath = $request->file('featured_image')->store('articles', 'public');
            $validatedData['featured_image_path'] = $imagePath;
        }

        // 3. Perbarui slug jika judul berubah
        if ($validatedData['title'] !== $article->title) {
            $validatedData['slug'] = Str::slug($validatedData['title']);
        }

        // 4. Update data artikel
        $article->update($validatedData);

        // 5. Redirect kembali ke halaman index
        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // Hapus gambar dari storage
        if ($article->featured_image_path) {
            Storage::disk('public')->delete($article->featured_image_path);
        }

        // Hapus data dari database
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }
}
