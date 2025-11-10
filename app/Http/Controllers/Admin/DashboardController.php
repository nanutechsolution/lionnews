<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // ----- Ambil Statistik Kunci -----
        $publishedCount = Article::where('status', Article::STATUS_PUBLISHED)->count();
        $draftCount = Article::where('status', Article::STATUS_DRAFT)->count();
        $pendingCount = Article::where('status', Article::STATUS_PENDING)->count();
        $userCount = User::count();

        // ----- Ambil Data Aksi Cepat (Paling Penting) -----
        $pendingArticles = Article::with('user', 'category')
                            ->where('status', Article::STATUS_PENDING)
                            ->latest()
                            ->take(5)
                            ->get();

        // ----- Ambil Data Artikel Saran Tag (jika ada) -----
        $suggestedArticles = Article::with('user')
                            ->whereNotNull('suggested_tags')
                            ->where('status', Article::STATUS_PENDING)
                            ->latest()
                            ->get();

        // ----- 2. Dapatkan Nama & Waktu Saat Ini -----
        $userName = Auth::user()->name;
        $hour = now()->hour;

        if ($hour < 12) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour < 18) {
            $greeting = 'Selamat Siang';
        } else {
            $greeting = 'Selamat Malam';
        }

        return view('admin.dashboard', [
            'publishedCount' => $publishedCount,
            'draftCount' => $draftCount,
            'pendingCount' => $pendingCount,
            'userCount' => $userCount,
            'pendingArticles' => $pendingArticles,
            'suggestedArticles' => $suggestedArticles,
            'userName' => $userName, // <-- 3. Kirim data baru
            'greeting' => $greeting, // <-- 3. Kirim data baru
        ]);
    }
}
