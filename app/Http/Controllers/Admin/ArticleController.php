<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get(); // <-- AMBIL DATA TAG

        return view('admin.articles.create', [
            'categories' => $categories,
            'tags' => $tags
        ]);
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
            'status' => [ // Validasi status
                'nullable', // Boleh null jika jurnalis
                // Pastikan nilainya salah satu dari konstanta kita
                Rule::in([Article::STATUS_DRAFT, Article::STATUS_PENDING, Article::STATUS_PUBLISHED]),
            ]
        ]);

        $status = Article::STATUS_DRAFT;
        $published_at = null;

        if (auth()->user()->can('publish-article')) {
            // Jika mereka mengirim status, gunakan itu
            $status = $request->input('status', Article::STATUS_DRAFT);
        } else {
            // Jurnalis hanya bisa mengirim 'Pending Review'
            $status = Article::STATUS_PENDING;
        }

        // Jika status diubah jadi 'Published', set waktu terbit
        if ($status === Article::STATUS_PUBLISHED) {
            $published_at = now();
        }
        // 2. Handle upload gambar
        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            // Simpan gambar di 'storage/app/public/articles'
            // Buat folder 'articles' jika belum ada
            $imagePath = $request->file('featured_image')->store('articles', 'public');
        }

        // 3. Buat slug dan tambahkan data yang divalidasi
        $article = Article::create([
            'title' => $validatedData['title'],
            'category_id' => $validatedData['category_id'],
            'excerpt' => $validatedData['excerpt'],
            'body' => $validatedData['body'],
            'featured_image_path' => $imagePath,
            'user_id' => Auth::id(), // Ambil ID user yang sedang login
            'slug' => Str::slug($validatedData['title']), // Buat slug otomatis
            'status' => $status, // <-- Gunakan status dinamis
            'published_at' => $published_at, // <-- Gunakan waktu terbit dinamis
        ]);
        $article->tags()->sync($request->input('tags', []));
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
        $tags = Tag::orderBy('name')->get();

        return view('admin.articles.edit', [
            'article' => $article,
            'categories' => $categories,
            'tags' => $tags // <-- KIRIM KE VIEW
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
            'status' => [
                'nullable',
                Rule::in([Article::STATUS_DRAFT, Article::STATUS_PENDING, Article::STATUS_PUBLISHED]),
            ]
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

        if (auth()->user()->can('publish-article')) {
            $newStatus = $request->input('status', $article->status);

            // Cek jika status BARU 'published' DAN status LAMA BUKAN 'published'
            if ($newStatus === Article::STATUS_PUBLISHED && $article->status !== Article::STATUS_PUBLISHED) {
                // Ini adalah pertama kalinya dipublish
                $validatedData['published_at'] = now();
            }
            // Cek jika status diubah DARI 'published' ke 'draft'
            else if ($newStatus !== Article::STATUS_PUBLISHED && $article->status === Article::STATUS_PUBLISHED) {
                // Tarik kembali (un-publish)
                $validatedData['published_at'] = null;
            }

            $validatedData['status'] = $newStatus;

        } else {
            // Jurnalis mengedit, setel kembali ke Pending Review
            $validatedData['status'] = Article::STATUS_PENDING;
            $validatedData['published_at'] = null; // Un-publish jika sedang diedit
        }
        // 3. Perbarui slug jika judul berubah
        if ($validatedData['title'] !== $article->title) {
            $validatedData['slug'] = Str::slug($validatedData['title']);
        }

        // 4. Update data artikel
        $article->update($validatedData);
        $article->tags()->sync($request->input('tags', []));

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
