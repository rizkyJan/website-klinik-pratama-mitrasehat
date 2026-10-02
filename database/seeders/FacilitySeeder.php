<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['name' => 'Ruang Pemeriksaan', 'sort_order' => 1],
            ['name' => 'Ruang Tindakan', 'sort_order' => 2],
            ['name' => 'Laboratorium', 'sort_order' => 3],
            ['name' => 'Farmasi', 'sort_order'  => 4],
            ['name' => 'Fisioterapi', 'sort_order' => 5],
            ['name' => 'KIA-KB', 'sort_order' => 6],
        ];

        foreach ($facilities as $facility) {
            Facility::updateOrCreate(['name' => $facility['name']], $facility);
        }
    }
}