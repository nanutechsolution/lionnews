<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Media;
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
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:4048', // maks 2MB
            'status' => [ // Validasi status
                'nullable', // Boleh null jika jurnalis
                Rule::in([Article::STATUS_DRAFT, Article::STATUS_PENDING, Article::STATUS_PUBLISHED]),
            ],
            'is_hero_pinned' => 'nullable|boolean',
            'is_editors_pick' => 'nullable|boolean',
        ], [
            'featured_image.max'   => 'Ukuran gambar terlalu besar. Maksimal 4MB.',
            'featured_image.image' => 'File yang diunggah harus berupa gambar.',
            'featured_image.mimes' => 'Format gambar harus JPG atau PNG.',

            'title.required'  => 'Judul wajib diisi.',
            'category_id.required' => 'Kategori belum dipilih.',
            'body.required' => 'Isi artikel tidak boleh kosong.',
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
            'user_id' => Auth::id(),
            'slug' => Str::slug($validatedData['title']),
            'status' => $status,
            'published_at' => $published_at,
            'is_hero_pinned' => $request->has('is_hero_pinned'),
            'is_editors_pick' => $request->has('is_editors_pick'),
        ]);
        if ($request->hasFile('featured_image')) {
            // Upload gambar baru
            $article->clearMediaCollection('featured_image');
            $article->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        } elseif ($request->filled('selected_media_id')) {
            // Gunakan gambar dari pustaka yang sudah ada
            $media = Media::find($request->input('selected_media_id'));
            if ($media) {
                $article->clearMediaCollection('featured_image'); // Hapus yang lama
                $media->copy($article, 'featured_image'); // Salin media ke artikel ini
            }
        } else {
        }
        $article->tags()->sync($request->input('tags', []));
        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel baru berhasil disimpan.');
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
    public function edit(Article $article)
    {
        // Cek Otorisasi (Contoh: Jurnalis hanya boleh edit artikelnya sendiri)
        // $this->authorize('update', $article); // Kita akan buat Policy ini nanti

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.articles.edit', [
            'article' => $article,
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    public function update(Request $request, Article $article)
    {
        // ============================
        // 1. VALIDASI INPUT
        // ============================
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:articles,title,' . $article->id,
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'required|string',
            'body' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
            'featured_image_caption' => 'nullable|string|max:500',
            'status' => [
                'nullable',
                Rule::in([
                    Article::STATUS_DRAFT,
                    Article::STATUS_PENDING,
                    Article::STATUS_PUBLISHED
                ]),
            ],
        ], [
            // Validasi bahasa Indonesia agar user awam paham
            'title.required' => 'Judul wajib diisi.',
            'title.unique' => 'Judul sudah digunakan, silakan pilih judul lain.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'excerpt.required' => 'Kutipan singkat wajib diisi.',
            'body.required' => 'Isi artikel tidak boleh kosong.',
            'featured_image.image' => 'File harus berupa gambar.',
            'featured_image.max' => 'Ukuran gambar maksimal 5 MB.',
            'featured_image_caption.max' => 'Caption maksimal 500 karakter.',
        ]);

        // ============================
        // 2. STATUS ARTIKEL (PERMISSION)
        // ============================
        if (auth()->user()->can('publish-article')) {
            $newStatus = $request->input('status', $article->status);

            if ($newStatus === Article::STATUS_PUBLISHED && $article->status !== Article::STATUS_PUBLISHED) {
                $validatedData['published_at'] = now();
            } elseif ($newStatus !== Article::STATUS_PUBLISHED && $article->status === Article::STATUS_PUBLISHED) {
                $validatedData['published_at'] = null;
            }

            $validatedData['status'] = $newStatus;
        } else {
            // Non-publisher otomatis masuk "Pending"
            $validatedData['status'] = Article::STATUS_PENDING;
            $validatedData['published_at'] = null;
        }

        // ============================
        // 3. UPDATE SLUG (JIKA JUDUL BERUBAH)
        // ============================
        if ($validatedData['title'] !== $article->title) {
            $validatedData['slug'] = Str::slug($validatedData['title']);
        }

        // ============================
        // 4. HANDLE CHECKBOX KHUSUS
        // ============================
        $validatedData['is_hero_pinned'] = $request->has('is_hero_pinned');
        $validatedData['is_editors_pick'] = $request->has('is_editors_pick');

        // ============================
        // 5. BUANG INPUT CAPTION AGAR TIDAK MASUK KE TABEL ARTICLES
        // ============================
        unset($validatedData['featured_image']);
        unset($validatedData['featured_image_caption']); // WAJIB

        // ============================
        // 6. UPDATE ARTIKEL
        // ============================
        $article->update($validatedData);

        // ============================
        // 7. HANDLE GAMBAR UTAMA
        // ============================
        if ($request->hasFile('featured_image')) {

            // Hapus media lama
            $oldMedia = $article->getFirstMedia('featured_image');
            if ($oldMedia) {
                $oldMedia->delete();
            }

            // Upload media baru + caption
            $article
                ->addMediaFromRequest('featured_image')
                ->withCustomProperties([
                    'caption' => $request->input('featured_image_caption')
                ])
                ->toMediaCollection('featured_image');
        } else if ($request->filled('featured_image_caption')) {

            // Update caption media lama jika tidak upload gambar baru
            $media = $article->getFirstMedia('featured_image');
            if ($media) {
                $media->setCustomProperty('caption', $request->input('featured_image_caption'));
                $media->save();
            }
        }

        // ============================
        // 8. SYNC TAG
        // ============================
        $article->tags()->sync($request->input('tags', []));

        // ============================
        // 9. REDIRECT
        // ============================
        return redirect()
            ->route('admin.articles.index')
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
