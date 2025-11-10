<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'published_at' => 'datetime',
        'suggested_tags' => 'array',
    ];

    /**
     * Mendapatkan semua artikel yang memiliki tag ini.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_tag');
    }


    /**
     * Mendapatkan 'key' route untuk model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug'; // Beri tahu Laravel untuk menggunakan 'slug'
    }
}
