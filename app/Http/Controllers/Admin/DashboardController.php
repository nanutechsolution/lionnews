<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ----- Ambil Statistik Kunci -----
        $publishedCount = Article::where('status', Article::STATUS_PUBLISHED)->count();
        $draftCount = Article::where('status', Article::STATUS_DRAFT)->count();
        $pendingCount = Article::where('status', Article::STATUS_PENDING)->count();
        $userCount = User::count(); // Jumlah semua pengguna

        // ----- Ambil Data Aksi Cepat (Paling Penting) -----
        // Ambil 5 artikel terbaru yang butuh review
        $pendingArticles = Article::with('user', 'category')
                            ->where('status', Article::STATUS_PENDING)
                            ->latest()
                            ->take(5)
                            ->get();

        return view('admin.dashboard', [
            'publishedCount' => $publishedCount,
            'draftCount' => $draftCount,
            'pendingCount' => $pendingCount,
            'userCount' => $userCount,
            'pendingArticles' => $pendingArticles,
        ]);
    }
}