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

        $oldContent = 'Bagi pasien yang memiliki akun Mobile JKN, pemeriksaan online dapat dilakukan melalui menu TELEHEALTH: login ke Mobile JKN, pilih TELEHEALTH, pilih Poli Umum (Dokter) yang aktif, pilih Chat, lalu isi kondisi kesehatan. Setelah masuk kolom chat, kirim Nama, NIK, Keluhan, Alergi Obat, dan Berat Badan. Setelah berhasil masuk kolom chat, pasien diminta mengonfirmasi melalui WhatsApp klinik.';

        $newContent = <<<'TEXT'
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

        // Jangan menimpa jawaban yang sudah pernah diedit admin. Update hanya seed bawaan lama.
        DB::table('ai_knowledge')
            ->where('title', 'Pendaftaran Online melalui Mobile JKN Telehealth')
            ->where('content', $oldContent)
            ->update([
                'content' => $newContent,
                'keywords' => 'cara daftar mobile jkn mobile jkn telehealth mendaftar pendaftaran online chat poli umum bpjs konsultasi online langkah prosedur',
                'updated_at' => now(),
            ]);

        // Knowledge WhatsApp tetap fokus pada pertanyaan tentang WhatsApp supaya tidak bersaing
        // dengan panduan Mobile JKN saat pengguna bertanya "cara daftar Mobile JKN".
        DB::table('ai_knowledge')
            ->where('title', 'Pendaftaran Online Tidak Dilayani melalui WhatsApp')
            ->where('keywords', 'daftar whatsapp pendaftaran wa bpjs mobile jkn daftar online')
            ->update([
                'keywords' => 'daftar whatsapp pendaftaran wa bpjs daftar online tidak dilayani',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('ai_knowledge')) {
            return;
        }

        $newContent = <<<'TEXT'
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

        DB::table('ai_knowledge')
            ->where('title', 'Pendaftaran Online melalui Mobile JKN Telehealth')
            ->where('content', $newContent)
            ->update([
                'content' => 'Bagi pasien yang memiliki akun Mobile JKN, pemeriksaan online dapat dilakukan melalui menu TELEHEALTH: login ke Mobile JKN, pilih TELEHEALTH, pilih Poli Umum (Dokter) yang aktif, pilih Chat, lalu isi kondisi kesehatan. Setelah masuk kolom chat, kirim Nama, NIK, Keluhan, Alergi Obat, dan Berat Badan. Setelah berhasil masuk kolom chat, pasien diminta mengonfirmasi melalui WhatsApp klinik.',
                'keywords' => 'mobile jkn telehealth daftar online pendaftaran online chat poli umum bpjs konsultasi online',
                'updated_at' => now(),
            ]);

        DB::table('ai_knowledge')
            ->where('title', 'Pendaftaran Online Tidak Dilayani melalui WhatsApp')
            ->where('keywords', 'daftar whatsapp pendaftaran wa bpjs daftar online tidak dilayani')
            ->update([
                'keywords' => 'daftar whatsapp pendaftaran wa bpjs mobile jkn daftar online',
                'updated_at' => now(),
            ]);
    }
};
