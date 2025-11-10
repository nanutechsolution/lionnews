<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    /**
     * Mengembalikan daftar semua media (gambar) dalam format JSON.
     * Dapat difilter dan dipaginasi.
     */
    public function index(Request $request)
    {
        $query = Media::orderBy('created_at', 'desc');

        // Filter berdasarkan jenis media (hanya gambar)
        $query->where('mime_type', 'like', 'image/%');

        // Pencarian (opsional)
        if ($request->has('search') && $request->input('search') !== '') {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('file_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('custom_properties->caption', 'like', '%' . $searchTerm . '%');
            });
        }

        // Paginate hasilnya
        $mediaItems = $query->paginate(12); // 12 gambar per halaman modal

        // Format data untuk frontend (URL, thumbnail, dll.)
        $formattedMedia = $mediaItems->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(), // URL lengkap gambar asli
                'thumb_url' => $media->getUrl('thumb'), // URL thumbnail (kita akan buat ini)
                'alt_text' => $media->getCustomProperty('alt_text') ?? pathinfo($media->file_name, PATHINFO_FILENAME),
                'caption' => $media->getCustomProperty('caption') ?? null,
                'file_name' => $media->file_name,
                'created_at_formatted' => $media->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'data' => $formattedMedia,
            'links' => $mediaItems->linkCollection(), // Untuk pagination links
            'meta' => [
                'current_page' => $mediaItems->currentPage(),
                'last_page' => $mediaItems->lastPage(),
                'total' => $mediaItems->total(),
            ],
        ]);
    }
}
