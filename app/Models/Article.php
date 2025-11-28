<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Enums\Fit;

class Article extends Model implements HasMedia, Viewable
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory, InteractsWithMedia, InteractsWithViews;

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

        $this->addMediaConversion('og-image')
            ->fit(Fit::Crop, 600, 600) // Ukuran kotak (WA suka kotak)
            ->quality(70)              // Kualitas diturunkan sedikit agar size < 300KB
            ->optimize()
            ->nonQueued();
    }



    public function comments(): HasMany
    {
        // Ambil komentar, urutkan dari yang terbaru
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Mengambil isi body yang sudah diproses (termasuk video embeds).
     * Di Blade, panggil dengan: {!! $article->processed_body !!}
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function processedBody(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->embedVideos($attributes['body']),
        );
    }

    /**
     * Helper function untuk mendeteksi dan mengganti link video.
     *
     * @param string $body
     * @return string
     */
    private function embedVideos(string $body): string
    {
        if (empty($body)) {
            return $body;
        }

        // Wrapper CSS untuk <iframe> responsif 16:9
        $wrapperStart = '<div class="responsive-video-wrapper">';
        $wrapperEnd = '</div>';

        // Daftar pola Regex untuk setiap platform
        // Kita menargetkan link yang berada di paragrafnya sendiri
        $patterns = [

            // Pola 1: YouTube (Long & Short)
            '~<p>(?:<a[^>]*>)?(https?:\/\/(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11}))[^<]*?(?:<\/a>)?<\/p>~' => function ($matches) use ($wrapperStart, $wrapperEnd) {
                // $matches[2] adalah ID video. Kita amankan dari XSS.
                $embedUrl = 'https://www.youtube.com/embed/' . htmlspecialchars($matches[2]);
                return $wrapperStart . '<iframe src="' . $embedUrl . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' . $wrapperEnd;
            },

            // Pola 2: Vimeo
            '~<p>(?:<a[^>]*>)?(https?:\/\/(?:www\.)?vimeo\.com\/(\d+))[^<]*?(?:<\/a>)?<\/p>~' => function ($matches) use ($wrapperStart, $wrapperEnd) {
                $embedUrl = 'https://player.vimeo.com/video/' . htmlspecialchars($matches[2]);
                return $wrapperStart . '<iframe src="' . $embedUrl . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>' . $wrapperEnd;
            },

            // Pola 3: TikTok (Format /video/)
            // TikTok butuh wrapper khusus (bukan 16:9)
            '~<p>(?:<a[^>]*>)?(https?:\/\/(?:www\.)?tiktok\.com\/@[a-zA-Z0-9._-]+\/video\/(\d+))[^<]*?(?:<\/a>)?<\/p>~' => function ($matches) {
                $embedUrl = 'https://www.tiktok.com/embed/v2/' . htmlspecialchars($matches[2]);
                // Kita tambahkan style inline untuk TikTok agar ukurannya pas
                return '<div class="responsive-video-wrapper-tiktok"><iframe src="' . $embedUrl . '" style="width:100%; height:750px; max-width: 325px; margin: auto; border:0;" allowfullscreen></iframe></div>';
            },

            // Pola 4: Facebook (Reels, Share/V, Video, Watch)
            '~<p>(?:<a[^>]*>)?(https?:\/\/(?:www\.)?facebook\.com\/(?:reel\/|share\/v\/|watch\/\?v=|video\.php\?v=|[a-zA-Z0-9._-]+\/videos\/)[^<]+)(?:<\/a>)?<\/p>~' => function ($matches) use ($wrapperStart, $wrapperEnd) {
                // $matches[1] adalah URL lengkap
                $cleanUrl = strtok($matches[1], '?'); // Bersihkan parameter
                $embedUrl = 'https://www.facebook.com/plugins/video.php?href=' . urlencode($cleanUrl) . '&show_text=false&width=560';
                return $wrapperStart . '<iframe src="' . $embedUrl . '" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>' . $wrapperEnd;
            },
        ];

        // Proses semua pola regex
        return preg_replace_callback_array($patterns, $body);
    }
}
