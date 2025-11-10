<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Tampilkan halaman pengaturan.
     * Kita gunakan 'firstOrCreate' untuk memastikan baris '1' selalu ada.
     */
    public function index()
    {
        $settings = Setting::firstOrCreate(['id' => 1]);
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update pengaturan.
     */
    public function update(Request $request)
    {
        // 2. Ambil baris pengaturan kita
        $settings = Setting::find(1);

        // 3. Validasi
        $validated = $request->validate([
            'breaking_news_active' => 'nullable|boolean',
            'breaking_news_text' => 'nullable|string|max:255',
            'breaking_news_link' => 'nullable|string|max:255',
        ]);

        // 4. Update
        $settings->update([
            'breaking_news_active' => $request->has('breaking_news_active'),
            'breaking_news_text' => $validated['breaking_news_text'],
            'breaking_news_link' => $validated['breaking_news_link'],
        ]);

        // 5. HAPUS CACHE AGAR PERUBAHAN LANGSUNG TAYANG
        Cache::forget('breaking_news_settings');

        return back()->with('success', 'Pengaturan Breaking News berhasil diperbarui.');
    }
}
