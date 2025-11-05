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
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Article::with('user', 'category')->latest();
        // Terapkan filter pencarian HANYA JIKA $search ada
        $query->when($search, function ($q, $search) {
            // 'where' untuk judul
            $q->where('title', 'like', "%{$search}%")
                // 'orWhereHas' untuk mencari di relasi 'user'
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                });
        });

        // Sekarang, ambil hasilnya dengan paginasi
        $articles = $query->paginate(15);

        return view('admin.articles.index', compact('articles'));
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
        if ($request->hasFile('featured_image')) {
            $article
                ->addMediaFromRequest('featured_image') // Ambil file
                ->withCustomProperties(['caption' => $request->input('featured_image_caption')])
                ->toMediaCollection('featured'); // Simpan ke koleksi 'featured'
        }
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
            'title' => 'required|string|max:255|unique:articles,title,' . $article->id,
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'required|string',
            'body' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Tambahkan webp
            'status' => [
                'nullable',
                Rule::in([Article::STATUS_DRAFT, Article::STATUS_PENDING, Article::STATUS_PUBLISHED]),
            ]
        ]);

        // 2. Handle upload gambar (jika ada gambar baru)

        if (auth()->user()->can('publish-article')) {
            $newStatus = $request->input('status', $article->status);
            if ($newStatus === Article::STATUS_PUBLISHED && $article->status !== Article::STATUS_PUBLISHED) {
                $validatedData['published_at'] = now();
            } else if ($newStatus !== Article::STATUS_PUBLISHED && $article->status === Article::STATUS_PUBLISHED) {
                $validatedData['published_at'] = null;
            }
            $validatedData['status'] = $newStatus;
        } else {
            $validatedData['status'] = Article::STATUS_PENDING;
            $validatedData['published_at'] = null;
        }
        // 3. Perbarui slug jika judul berubah
        if ($validatedData['title'] !== $article->title) {
            $validatedData['slug'] = Str::slug($validatedData['title']);
        }
        unset($validatedData['featured_image']);
        // 4. Update data artikel
        $article->update($validatedData);
        if ($request->hasFile('featured_image')) {
            $article
                ->addMediaFromRequest('featured_image')
                // TAMBAHKAN BARIS INI:
                ->withCustomProperties(['caption' => $request->input('featured_image_caption')])
                ->toMediaCollection('featured');
        } else if ($request->filled('featured_image_caption')) {
            // Ambil media yang ada dan perbarui saja propertinya
            $media = $article->getFirstMedia('featured');
            if ($media) {
                $media->setCustomProperty('caption', $request->input('featured_image_caption'));
                $media->save(); // Jangan lupa simpan
            }
        }
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
