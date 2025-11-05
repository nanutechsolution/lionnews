<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia; 
use Spatie\MediaLibrary\InteractsWithMedia; 
use Spatie\MediaLibrary\MediaCollections\Models\Media; 

class Article extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory, InteractsWithMedia;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending_review';
    public const STATUS_PUBLISHED = 'published';


    /**
     * Mengizinkan semua field diisi, kecuali yang dijaga (tidak ada).
     * Alternatif dari $fillable.
     */
    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime', 
    ];

    /**
     * Mendapatkan user (penulis) yang memiliki artikel ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan kategori dari artikel ini.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Mendapatkan semua tag dari artikel ini.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    /**
     * Mendapatkan 'key' route untuk model.
     * * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug'; // Beri tahu Laravel untuk menggunakan 'slug' bukan 'id'
    }

    /**
     * Daftarkan koleksi media (featured).
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('featured') // Nama koleksi kita
            ->singleFile();
    }

    /**
     * Tentukan konversi media (thumbnail, dll.)
     * Ini adalah inti dari optimasi kita.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('featured-thumbnail') // Untuk di homepage/card
            ->width(400)
            ->height(250)
            ->sharpen(10)
            ->nonQueued(); // Lakukan konversi langsung

        $this
            ->addMediaConversion('featured-large') // Untuk di halaman artikel
            ->width(1200)
            ->height(675)
            ->nonQueued();
    }
}
