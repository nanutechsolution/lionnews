<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Simpan komentar baru.
     */
    public function store(Request $request, Article $article)
    {
        // 1. Validasi
        $validated = $request->validate([
            'body' => 'required|string|max:2500', // Batasi 2500 karakter
        ]);

        // 2. Buat Komentar
        $article->comments()->create([
            'user_id' => Auth::id(), // Ambil ID user yang sedang login
            'body' => $validated['body'],
        ]);

        // 3. Redirect kembali ke artikel
        return back()->with('success', 'Komentar Anda berhasil diposting.');
    }
}