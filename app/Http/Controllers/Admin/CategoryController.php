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
            'is_featured' => 'nullable|boolean',
            'nav_order' => 'nullable|integer',
            'parent_id' => 'nullable|exists:categories,id', // <-- Validasi baru
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_featured' => $validated['is_featured'] ?? false,
            'nav_order' => $validated['nav_order'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null, // <-- Simpan data baru
        ]);

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
        $categories = Category::where('id', '!=', $category->id)
            ->orderBy('name', 'asc')->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'is_featured' => 'nullable|boolean',
            'nav_order' => 'nullable|integer',
            'parent_id' => 'nullable|exists:categories,id', // <-- Validasi baru
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_featured' => $validated['is_featured'] ?? false,
            'nav_order' => $validated['nav_order'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null, // <-- Simpan data baru
        ]);

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
