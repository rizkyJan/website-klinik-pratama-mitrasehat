<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Klinik', 'Ruang Pelayanan', 'Fasilitas', 'Kegiatan Prolanis', 'Event Kesehatan', 'Pelayanan'];

        foreach ($categories as $i => $category) {
            Gallery::updateOrCreate(
                ['category' => $category],
                ['category' => $category, 'sort_order' => $i + 1]
            );
        }
    }
}