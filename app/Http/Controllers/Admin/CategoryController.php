<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 'withCount' untuk menghitung relasi 'articles'
        $categories = Category::withCount('articles')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'is_featured' => 'nullable|boolean', // Validasi field baru
            'nav_order' => 'nullable|integer',   // Validasi field baru
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_featured' => $validated['is_featured'] ?? false, // Simpan field baru
            'nav_order' => $validated['nav_order'] ?? null,    // Simpan field baru
        ]);
        
        // PENTING: Hapus cache navigasi
        Cache::forget('navigation_categories');

        return redirect()->route('admin.categories.index')->with('success', 'Kategori baru dibuat.');
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
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'is_featured' => 'nullable|boolean', // Validasi field baru
            'nav_order' => 'nullable|integer',   // Validasi field baru
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_featured' => $validated['is_featured'] ?? false, // Simpan field baru
            'nav_order' => $validated['nav_order'] ?? null,    // Simpan field baru
        ]);

        // PENTING: Hapus cache navigasi agar perubahan menu terlihat
        Cache::forget('navigation_categories');

        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // PENTING: Mencegah penghapusan jika masih ada artikel
        if ($category->articles()->count() > 0) {
            return back()->with('error', 'Tidak bisa menghapus kategori yang masih memiliki artikel.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori dihapus.');
    }
}
