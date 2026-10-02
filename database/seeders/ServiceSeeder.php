<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceSchedule;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Poli Umum', 'description' => 'Pemeriksaan dan konsultasi kesehatan umum.'],
            ['name' => 'Poli Gigi', 'description' => 'Perawatan dan pemeriksaan gigi & mulut.'],
            ['name' => 'KIA-KB', 'description' => 'Kesehatan ibu, anak, dan keluarga berencana.'],
            ['name' => 'Laboratorium', 'description' => 'Pemeriksaan laboratorium yang tersedia.'],
            ['name' => 'Fisioterapi', 'description' => 'Membantu pemulihan fungsi gerak dan fisik.'],
            ['name' => 'Akupuntur', 'description' => 'Pelayanan akupuntur sesuai kebutuhan.'],
            ['name' => 'Farmasi', 'description' => 'Pelayanan obat dan informasi penggunaannya.'],
            ['name' => 'Paket Cek Sehat', 'description' => 'Pilihan pemeriksaan kesehatan.'],
            ['name' => 'Booster Vitamin', 'description' => 'Layanan sesuai kebutuhan dan ketentuan medis.'],
        ];

        foreach ($services as $i => $service) {
            $slug = \Illuminate\Support\Str::slug($service['name']);
            $s = Service::updateOrCreate(
                ['slug' => $slug],
                array_merge($service, ['slug' => $slug, 'sort_order' => $i + 1])
            );

            // Add schedule for each service
            ServiceSchedule::updateOrCreate(
                ['service_id' => $s->id, 'day' => 'Senin-Sabtu'],
                [
                    'service_id' => $s->id,
                    'day' => 'Senin-Sabtu',
                    'open_time' => $service['name'] === 'Poli Umum' ? null : '08:00',
                    'close_time' => $service['name'] === 'Poli Umum' ? null : '20:00',
                    'is_24h' => $service['name'] === 'Poli Umum',
                ]
            );
        }
    }
}