<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;


    /**
     * Mendapatkan semua artikel yang memiliki tag ini.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_tag');
    }
}
