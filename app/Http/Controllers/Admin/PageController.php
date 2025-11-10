<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Tampilkan daftar semua halaman.
     */
    public function index()
    {
        $pages = Page::latest()->paginate(20);
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Tampilkan form untuk membuat halaman baru.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Simpan halaman baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:pages',
            'body' => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ]);

        Page::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'body' => $validated['body'] ?? '',
            'is_published' => $request->has('is_published'), // Cek jika checkbox dicentang
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Halaman baru berhasil dibuat.');
    }

    /**
     * Tampilkan form untuk mengedit halaman.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update halaman yang ada di database.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:pages,title,' . $page->id,
            'body' => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ]);

        $page->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']), // Update slug jika judul berubah
            'body' => $validated['body'] ?? '',
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil diperbarui.');
    }

    /**
     * Hapus halaman dari database.
     */
    public function destroy(Page $page)
    {
        // Peringatan: Hati-hati menghapus halaman inti
        if (in_array($page->slug, ['tentang-kami', 'redaksi', 'pedoman-media-siber'])) {
            return back()->with('error', 'Halaman ini tidak boleh dihapus.');
        }

        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil dihapus.');
    }
}
