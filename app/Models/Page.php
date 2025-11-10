<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    /**
     * Mengizinkan semua field diisi (mass assignable).
     */
    protected $guarded = [];

    /**
     * Mengubah field 'is_published' menjadi boolean.
     */
    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Memberi tahu Laravel untuk menggunakan 'slug'
     * sebagai kunci di URL, bukan 'id'.
     *
     * Ini penting untuk route: Route::get('/{page:slug}', ...)
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
