<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $guarded = [];


    /**
     * Mendapatkan kategori Induk (Parent).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Mendapatkan semua Sub-Kategori (Anak/Children).
     */
    public function children(): HasMany
    {
        // Urutkan sub-kategori berdasarkan nama
        return $this->hasMany(Category::class, 'parent_id')->orderBy('name', 'asc');
    }


    /**
     * Mendapatkan semua artikel dalam kategori ini.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
