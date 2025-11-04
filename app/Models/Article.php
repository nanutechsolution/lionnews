<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory;

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
}
