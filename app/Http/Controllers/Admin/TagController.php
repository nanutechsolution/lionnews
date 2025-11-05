<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache; 
use App\Models\Tag;
class TagController extends Controller
{
    /**
     * Tampilkan daftar semua tag.
     */
    public function index()
    {
        $tags = Tag::withCount('articles')->latest()->get();
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Tampilkan form untuk membuat tag baru.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Simpan tag baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags',
        ]);

        Tag::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        // Hapus cache sidebar agar tag baru muncul
        Cache::forget('sidebar_popular_tags');

        return redirect()->route('admin.tags.index')->with('success', 'Tag baru berhasil dibuat.');
    }

    /**
     * Tampilkan form untuk mengedit tag.
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update tag yang ada di database.
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
        ]);

        $tag->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        // Hapus cache sidebar agar perubahan terlihat
        Cache::forget('sidebar_popular_tags');

        return redirect()->route('admin.tags.index')->with('success', 'Tag berhasil diperbarui.');
    }

    /**
     * Hapus tag dari database.
     */
    public function destroy(Tag $tag)
    {
        // Mencegah penghapusan jika tag masih digunakan
        if ($tag->articles()->count() > 0) {
            return back()->with('error', 'Tidak bisa menghapus tag yang masih memiliki artikel.');
        }

        $tag->delete();
        
        // Hapus cache sidebar
        Cache::forget('sidebar_popular_tags');

        return redirect()->route('admin.tags.index')->with('success', 'Tag berhasil dihapus.');
    }


    /**
     * Method search untuk Tom Select (AJAX).
     * INI YANG MUNGKIN HILANG.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        $tags = Tag::where('name', 'LIKE', "%{$query}%")
                    ->select('id', 'name') // Tom Select butuh id dan name
                    ->take(10) // Batasi hasil
                    ->get();
        // Format ulang agar Tom Select mengerti
        $formattedTags = $tags->map(fn($tag) => [
            'id' => $tag->id,
            'name' => $tag->name
        ]);

        return response()->json($formattedTags);
    }
}