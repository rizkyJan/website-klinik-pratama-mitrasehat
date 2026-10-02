<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            ['title' => '5 Tips Menjaga Kesehatan Gigi', 'category' => 'Tips Kesehatan'],
            ['title' => 'Kenali Layanan Fisioterapi', 'category' => 'Info Layanan'],
            ['title' => 'Bagaimana Alur Pelayanan BPJS?', 'category' => 'Info BPJS'],
            ['title' => 'Kegiatan Senam Prolanis', 'category' => 'Kegiatan Klinik'],
            ['title' => 'Mengenal Frozen Shoulder', 'category' => 'Edukasi'],
            ['title' => 'Tips Pola Makan Seimbang', 'category' => 'Tips Hidup Sehat'],
        ];

        foreach ($articles as $i => $article) {
            Article::updateOrCreate(
                ['title' => $article['title']],
                array_merge($article, ['sort_order' => $i + 1, 'published_at' => now()])
            );
        }
    }
}