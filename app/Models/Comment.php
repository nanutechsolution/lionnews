<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
    
    // Izinkan 'mass assignment'
    protected $guarded = [];

    // Relasi: Komentar ini milik satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Komentar ini milik satu Article
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}