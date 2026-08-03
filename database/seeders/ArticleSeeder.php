<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed categories
        $categories = ['Kajian Islam', 'Pengumuman', 'Kegiatan Masjid', 'Fiqih & Ibadah', 'Kisah Inspiratif'];
        foreach ($categories as $cat) {
            ArticleCategory::firstOrCreate(
                ['name' => $cat],
                ['slug' => Str::slug($cat)]
            );
        }

        // Seed tags
        $tags = ['ramadhan', 'zakat', 'kurban', 'sholat', 'sedekah', 'remaja masjid', 'covid-19', 'akhlak'];
        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['name' => $tag],
                ['slug' => Str::slug($tag)]
            );
        }

        // Article titles
        $titles = [
            'Keutamaan Sholat Tarawih di Bulan Ramadhan',
            'Panduan Lengkap Menghitung Zakat Maal',
            'Hikmah di Balik Ibadah Kurban',
            'Menjaga Silaturahmi di Tengah Kesibukan',
            'Adab-Adab Masuk Masjid yang Sering Terlupakan',
            'Pengumuman Jadwal Kajian Rutin Bulan Ini',
            'Renovasi Tempat Wudhu Selesai, Ini Fasilitas Barunya',
            'Kisah Inspiratif: Sedekah Subuh Sang Tukang Becak',
            'Tips Mendidik Anak Cinta Masjid Sejak Dini',
            'Menyambut Bulan Suci dengan Hati yang Bersih',
        ];

        // Get admin user
        $adminUser = User::role('admin')->first();

        if (!$adminUser) {
            $this->command->error('No admin user found. Please create an admin user first.');
            return;
        }

        // Create articles
        foreach ($titles as $i => $title) {
            // Status yang valid: draft atau published (sesuai dengan model)
            // Hanya 8 artikel pertama yang dipublish, sisanya draft
            $status = $i < 8 ? 'published' : 'draft';

            // Ambil category random
            $category = ArticleCategory::inRandomOrder()->first();

            $article = Article::create([
                'user_id' => $adminUser->id,
                'category_id' => $category ? $category->id : null, // Menggunakan category_id sesuai model
                'title' => $title,
                'slug' => Str::slug($title),
                'thumbnail' => 'seed-images/articles/artikel-' . ($i + 1) . '.jpg',
                'content' => '<p>' . fake()->paragraphs(6, true) . '</p>',
                'meta_keyword' => 'masjid, islam, ' . fake()->words(3, true),
                'status' => $status,
                'published_at' => $status === 'published' ? now()->subDays(rand(1, 200)) : null,
                'views_count' => $status === 'published' ? rand(15, 500) : 0, // Menggunakan views_count sesuai model
            ]);

            // Attach random tags to article
            $randomTags = Tag::inRandomOrder()->limit(rand(2, 4))->pluck('id');
            $article->tags()->attach($randomTags);
        }

        $this->command->info('Articles seeded successfully!');
    }
}
