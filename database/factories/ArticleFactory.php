<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6);
        $status = $this->faker->randomElement(['draft', 'published', 'archived']);

        // Di dalam method definition()
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'thumbnail' => 'placeholders/artikel.jpg', // <-- Diubah ke placeholder artikel
            'content' => collect($this->faker->paragraphs(4))->map(fn($p) => "<p>$p</p>")->implode("\n"),
            'meta_keyword' => implode(', ', $this->faker->words(3)),
            'status' => $status,
            'published_at' => $status === 'published' ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
