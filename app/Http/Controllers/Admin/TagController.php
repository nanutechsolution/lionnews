<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 'withCount' untuk menghitung relasi 'articles'
        $tags = Tag::withCount('articles')->latest()->get();
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
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

        return redirect()->route('admin.tags.index')->with('success', 'Kategori baru dibuat.');
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
    public function edit(Tag $Tag)
    {
        return view('admin.tags.edit', compact('Tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $Tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $Tag->id,
        ]);

        $Tag->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.tags.index')->with('success', 'Kategori diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $Tag)
    {
        // PENTING: Mencegah penghapusan jika masih ada artikel
        if ($Tag->articles()->count() > 0) {
            return back()->with('error', 'Tidak bisa menghapus kategori yang masih memiliki artikel.');
        }

        $Tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'Kategori dihapus.');
    }
}
