<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['question' => 'Di mana lokasi Klinik Mitra Sehat?', 'answer' => 'Klinik Pratama Mitra Sehat berada di Jl. Veteran No.70, Ngabeyan, Jetis, Kec. Sukoharjo, Kabupaten Sukoharjo, Jawa Tengah 57511.', 'icon' => 'location', 'sort_order' => 1],
            ['question' => 'Jam pelayanan Klinik Mitra Sehat sampai jam berapa?', 'answer' => 'Jam pelayanan mengikuti jadwal klinik dan masing-masing layanan. Silakan konfirmasi jadwal terbaru melalui WhatsApp 0813-1170-9726.', 'icon' => 'clock', 'sort_order' => 2],
            ['question' => 'Apakah Klinik Mitra Sehat menerima BPJS?', 'answer' => 'Untuk informasi layanan BPJS dan ketentuan yang berlaku, silakan konfirmasi kepada admin Klinik Mitra Sehat sebelum berkunjung.', 'icon' => 'check', 'sort_order' => 3],
            ['question' => 'Bagaimana cara mendaftar?', 'answer' => 'Pendaftaran dapat dilakukan melalui kanal pendaftaran yang tersedia. Hubungi admin melalui WhatsApp untuk mendapatkan informasi alur pendaftaran.', 'icon' => 'clipboard', 'sort_order' => 4],
            ['question' => 'Apakah bisa daftar melalui WhatsApp?', 'answer' => 'Silakan hubungi WhatsApp Klinik Mitra Sehat di 0813-1170-9726 untuk informasi dan pendaftaran.', 'icon' => 'chat', 'sort_order' => 5],
            ['question' => 'Layanan apa saja yang tersedia?', 'answer' => 'Klinik Mitra Sehat menyediakan Poli Umum, Poli Gigi, Poli KIA-KB, Laboratorium, Fisioterapi, Farmasi, dan Akupunktur.', 'icon' => 'cross', 'sort_order' => 6],
            ['question' => 'Bagaimana cara mengetahui jadwal dokter?', 'answer' => 'Jadwal dokter dapat berubah. Silakan cek informasi jadwal terbaru atau konfirmasi kepada admin sebelum datang.', 'icon' => 'calendar', 'sort_order' => 7],
            ['question' => 'Apakah bisa datang langsung tanpa daftar?', 'answer' => 'Untuk informasi mengenai pendaftaran langsung dan waktu tunggu, silakan konfirmasi terlebih dahulu kepada admin.', 'icon' => 'person', 'sort_order' => 8],
                        ['question' => 'Apakah tersedia pemeriksaan laboratorium?', 'answer' => 'Ya, Klinik Mitra Sehat memiliki layanan Laboratorium. Jenis pemeriksaan yang tersedia dapat ditanyakan kepada admin.', 'icon' => 'lab', 'sort_order' => 9],
            ['question' => 'Apakah tersedia Paket Cek Sehat?', 'answer' => 'Informasi Paket Cek Sehat, pilihan pemeriksaan, dan harga dapat ditanyakan kepada admin Klinik Mitra Sehat.', 'icon' => 'heart', 'sort_order' => 10],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }
    }
}