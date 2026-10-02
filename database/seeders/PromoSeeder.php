<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        $promos = [
            ['title' => 'Paket Cek Sehat', 'period' => 'Januari 2026', 'price' => 'Rp 150.000', 'description' => 'Diskon paket cek sehat untuk pemeriksaan awal.'],
            ['title' => 'Booster Vitamin', 'period' => 'Februari 2026', 'price' => 'Rp 200.000', 'description' => 'Promo booster vitamin untuk menjaga imunitas.'],
            ['title' => 'Promo Layanan', 'period' => 'Maret 2026', 'price' => 'Rp 100.000', 'description' => 'Promo khusus layanan tertentu.'],
        ];

        foreach ($promos as $i => $promo) {
            Promo::updateOrCreate(['title' => $promo['title']], array_merge($promo, ['sort_order' => $i + 1]));
        }
    }
}