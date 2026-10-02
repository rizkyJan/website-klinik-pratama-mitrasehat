<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HealthPackage;

class HealthPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket A',
                'price' => 'Rp 150.000',
                'items' => ['Pemeriksaan umum', 'Darah lengkap', 'Gula darah'],
                'sort_order' => 1,
            ],
            [
                'name' => 'Paket B',
                'price' => 'Rp 250.000',
                'items' => ['Pemeriksaan umum', 'Darah lengkap', 'Kolesterol', 'Asam urat'],
                'sort_order' => 2,
            ],
            [
                'name' => 'Paket C',
                'price' => 'Rp 350.000',
                'items' => ['Pemeriksaan umum', 'Darah lengkap', 'Kolesterol', 'Fungsi hati', 'Fungsi ginjal'],
                'sort_order' => 3,
            ],
        ];

        foreach ($packages as $pkg) {
            HealthPackage::updateOrCreate(['name' => $pkg['name']], $pkg);
        }
    }
}