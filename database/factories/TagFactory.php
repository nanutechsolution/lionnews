<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Buat nama tag yang unik
        $name = fake()->unique()->words(rand(1, 3), true); // Cth: "Pemilu 2024"

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
        ];
    }
}
