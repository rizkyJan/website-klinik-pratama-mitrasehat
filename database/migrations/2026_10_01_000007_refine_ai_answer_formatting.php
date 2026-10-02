<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ai_knowledge')) {
            return;
        }

        $previous = <<<'TEXT'
Untuk pemeriksaan/konsultasi online melalui Mobile JKN, gunakan menu TELEHEALTH dengan alur berikut:
1. Login ke aplikasi Mobile JKN menggunakan akun pasien yang akan diperiksa.
2. Pilih menu TELEHEALTH.
3. Pilih Poli Umum (Dokter) yang sedang aktif.
4. Pilih Chat, lalu isi kondisi kesehatan sesuai keluhan.
5. Setelah masuk ke kolom chat Mobile JKN, kirim format data: Nama, NIK, Keluhan, Alergi Obat, dan Berat Badan.
6. Setelah berhasil masuk ke kolom chat, konfirmasikan melalui WhatsApp Klinik Mitra Sehat agar dapat ditindaklanjuti petugas.

Aplikasi Mobile JKN dapat diunduh melalui https://bit.ly/AplikasiJKNMobile dan tutorial penggunaan tersedia di https://intip.in/VideoTutorialMJKN.

Catatan: NIK dan data pribadi pada langkah di atas dikirim melalui kanal resmi Mobile JKN sesuai alur pelayanan klinik, bukan melalui chatbot Asisten Klinik di website.
TEXT;

        $structured = <<<'TEXT'
Pendaftaran online melalui Mobile JKN menggunakan menu TELEHEALTH.

Langkah-langkah:
1. Login ke aplikasi Mobile JKN menggunakan akun pasien yang akan diperiksa.
2. Pilih menu TELEHEALTH.
3. Pilih Poli Umum (Dokter) yang sedang aktif.
4. Pilih Chat, lalu isi kondisi kesehatan sesuai keluhan.
5. Setelah masuk ke kolom chat Mobile JKN, kirim data berikut:
   • Nama
   • NIK
   • Keluhan
   • Alergi Obat
   • Berat Badan
6. Setelah berhasil masuk ke kolom chat, konfirmasikan melalui WhatsApp Klinik Mitra Sehat agar dapat ditindaklanjuti petugas.

Tautan:
• Download Mobile JKN: https://bit.ly/AplikasiJKNMobile
• Tutorial penggunaan: https://intip.in/VideoTutorialMJKN

Catatan privasi:
NIK dan data pribadi pada alur di atas dikirim melalui kanal resmi Mobile JKN sesuai proses pelayanan, bukan melalui chatbot Asisten Klinik di website.
TEXT;

        // Jangan menimpa jawaban yang sudah diedit admin secara manual.
        DB::table('ai_knowledge')
            ->where('title', 'Pendaftaran Online melalui Mobile JKN Telehealth')
            ->where('content', $previous)
            ->update([
                'content' => $structured,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('ai_knowledge')) {
            return;
        }

        $structured = <<<'TEXT'
Pendaftaran online melalui Mobile JKN menggunakan menu TELEHEALTH.

Langkah-langkah:
1. Login ke aplikasi Mobile JKN menggunakan akun pasien yang akan diperiksa.
2. Pilih menu TELEHEALTH.
3. Pilih Poli Umum (Dokter) yang sedang aktif.
4. Pilih Chat, lalu isi kondisi kesehatan sesuai keluhan.
5. Setelah masuk ke kolom chat Mobile JKN, kirim data berikut:
   • Nama
   • NIK
   • Keluhan
   • Alergi Obat
   • Berat Badan
6. Setelah berhasil masuk ke kolom chat, konfirmasikan melalui WhatsApp Klinik Mitra Sehat agar dapat ditindaklanjuti petugas.

Tautan:
• Download Mobile JKN: https://bit.ly/AplikasiJKNMobile
• Tutorial penggunaan: https://intip.in/VideoTutorialMJKN

Catatan privasi:
NIK dan data pribadi pada alur di atas dikirim melalui kanal resmi Mobile JKN sesuai proses pelayanan, bukan melalui chatbot Asisten Klinik di website.
TEXT;

        DB::table('ai_knowledge')
            ->where('title', 'Pendaftaran Online melalui Mobile JKN Telehealth')
            ->where('content', $structured)
            ->update([
                'content' => 'Untuk pemeriksaan/konsultasi online melalui Mobile JKN, gunakan menu TELEHEALTH sesuai alur resmi Klinik Mitra Sehat.',
                'updated_at' => now(),
            ]);
    }
};
