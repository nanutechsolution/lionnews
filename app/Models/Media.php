<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
class Media extends BaseMedia
{
    // Tambahkan konversi thumbnail di sini
    public function registerMediaConversions(): void
    {
        $this->addMediaConversion('thumb')
             ->width(150)
             ->height(150)
             ->sharpen(10);
    }
}
