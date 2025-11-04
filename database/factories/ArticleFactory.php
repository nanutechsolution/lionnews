<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
    {
        $title = fake()->unique()->sentence(rand(5, 10)); // Judul berita yang realistis
        
        // Buat 3-5 paragraf untuk isi berita
        $body = fake()->paragraphs(rand(3, 5), true); // 'true' untuk mengembalikan sebagai string
        
        // Bungkus paragraf dalam tag <p>
        $formattedBody = collect(explode("\n\n", $body))
                        ->map(fn($p) => "<p>{$p}</p>")
                        ->implode('');

        return [
            // Pilih ID user atau kategori secara acak
            // 'User::factory()' akan otomatis membuat user baru jika belum ada
            'user_id' => User::factory(), 
            'category_id' => Category::factory(),
            
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(2), // Kutipan singkat
            'body' => $formattedBody, // Isi berita dengan format HTML
            
            'featured_image_path' => null, // Kita biarkan null, atau bisa set placeholder
            
            'status' => Article::STATUS_PUBLISHED, // Default kita set 'published'
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'), // Waktu terbit acak
        ];
    }

    /**
     * Buat state untuk artikel 'draft'
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Article::STATUS_DRAFT,
            'published_at' => null,
        ]);
    }
    
    /**
     * Buat state untuk artikel 'pending'
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Article::STATUS_PENDING,
            'published_at' => null,
        ]);
    }
}
