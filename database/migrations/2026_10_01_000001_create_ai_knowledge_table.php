<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_knowledge', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category', 80)->default('Informasi Klinik');
            $table->text('content');
            $table->text('keywords')->nullable();
            $table->string('source', 40)->default('manual');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'category']);
        });

        // Pengetahuan awal dirapikan dari template WhatsApp resmi Klinik Mitra Sehat
        // yang diberikan untuk proyek ini. Admin dapat mengubah/menonaktifkannya kapan saja.
        $now = now();
        DB::table('ai_knowledge')->insert([
            [
                'title' => 'Pendaftaran dan Telekonsultasi',
                'category' => 'Pendaftaran',
                'content' => "Untuk telekonsultasi, pasien diminta mengisi Nama, NIK, Keluhan, Alergi Obat, dan Berat Badan. Untuk keluhan fisik yang tampak seperti luka, bengkak, gatal, atau canteng, pasien diminta menyertakan foto. Hasil laboratorium, buku obat, surat rujuk balik, atau surat kontrol juga dapat diminta bila relevan. Surat izin sakit hanya dapat dikeluarkan setelah pemeriksaan langsung oleh dokter.",
                'keywords' => 'telekonsultasi konsultasi online nama nik keluhan alergi obat berat badan foto luka bengkak gatal canteng surat sakit',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 10, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Pendaftaran Online melalui Mobile JKN Telehealth',
                'category' => 'Mobile JKN',
                'content' => "Bagi pasien yang memiliki akun Mobile JKN, pemeriksaan online dapat dilakukan melalui menu TELEHEALTH: login ke Mobile JKN, pilih TELEHEALTH, pilih Poli Umum (Dokter) yang aktif, pilih Chat, lalu isi kondisi kesehatan. Setelah masuk kolom chat, kirim Nama, NIK, Keluhan, Alergi Obat, dan Berat Badan. Setelah berhasil masuk kolom chat, pasien diminta mengonfirmasi melalui WhatsApp klinik.",
                'keywords' => 'mobile jkn telehealth daftar online pendaftaran online chat poli umum bpjs konsultasi online',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 20, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Pendaftaran Online Tidak Dilayani melalui WhatsApp',
                'category' => 'Pendaftaran',
                'content' => "Pendaftaran online tidak dilayani melalui WhatsApp. Bagi pengguna BPJS, pendaftaran online dilakukan melalui aplikasi Mobile JKN.",
                'keywords' => 'daftar whatsapp pendaftaran wa bpjs mobile jkn daftar online',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 30, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Pemantauan Antrean',
                'category' => 'Antrean',
                'content' => "Pasien disarankan memantau sisa antrean secara berkala melalui Mobile JKN dan melakukan refresh/penyegaran. Estimasi waktu pelayanan tidak selalu akurat karena durasi pemeriksaan setiap pasien dapat berbeda dan pasien sebelum nomor antrean dapat tidak hadir. Jika nomor antrean terlewat, pasien perlu mendaftar ulang atau mengambil nomor antrean baru.",
                'keywords' => 'antrean antrian nomor antrean estimasi waktu sisa antrean refresh mobile jkn terlewat',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 40, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Jadwal Pelayanan Poli Gigi',
                'category' => 'Poli Gigi',
                'content' => "Pelayanan gigi: Senin–Jumat pukul 08.00–12.00 WIB. Pendaftaran onsite untuk 6 pasien tercepat berlaku hari H pukul 08.00 WIB, dan pendaftaran online untuk 9 pasien tercepat melalui Mobile JKN dapat dilakukan H-1 mulai pukul 08.00 WIB. Sabtu pukul 13.00–17.00 WIB dan pendaftaran hanya melalui Mobile JKN H-1 mulai pukul 12.30 WIB. Tanggal merah/libur nasional tutup. Kuota pemeriksaan maksimal 15 orang per hari.",
                'keywords' => 'dokter gigi poli gigi jadwal gigi senin selasa rabu kamis jumat sabtu kuota 15 mobile jkn onsite',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 50, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Layanan Poli Gigi',
                'category' => 'Poli Gigi',
                'content' => "Pelayanan gigi meliputi administrasi, pemeriksaan/pengobatan/konsultasi medis, premedikasi, kegawatdaruratan oro-dental, pencabutan gigi sulung, pencabutan gigi permanen tanpa penyulit, obat pasca ekstraksi, tambalan komposit/GIC direct non-estetik, dan scaling/pembersihan karang gigi sesuai ketentuan.",
                'keywords' => 'layanan gigi cabut gigi tambal gigi scaling karang gigi konsultasi gigi oro dental',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 60, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Scaling Gigi BPJS dan Umum',
                'category' => 'Poli Gigi',
                'content' => "Untuk pasien BPJS, scaling gigi ditanggung satu tahun sekali bila terdapat indikasi medis, misalnya pembengkakan, perdarahan gusi, atau penumpukan karang gigi sesuai hasil pemeriksaan dokter gigi. Untuk pasien umum, scaling dilakukan setelah pemeriksaan dan dengan perjanjian dokter gigi; informasi template klinik menyebut kisaran biaya Rp200.000–Rp400.000.",
                'keywords' => 'scaling skeling karang gigi bpjs biaya umum 200 400 ribu pembersihan karang gigi',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 70, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Jadwal Pelayanan USG',
                'category' => 'USG',
                'content' => "Jadwal pelayanan USG: Senin dan Kamis pukul 15.30–18.00 WIB dengan pendaftaran melalui reservasi WhatsApp klinik dan onsite. Selasa pukul 15.30–18.00 WIB dengan pendaftaran melalui reservasi WhatsApp klinik. Melayani pasien umum dan BPJS dengan syarat dan ketentuan. Ketersediaan kuota dikonfirmasi lebih lanjut oleh bidan.",
                'keywords' => 'usg hamil kehamilan bidan reservasi senin selasa kamis 15.30 18.00 bpjs umum',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 80, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Surat Keterangan Sehat',
                'category' => 'Surat Keterangan',
                'content' => "Pengurusan Surat Keterangan Sehat dilakukan dengan pendaftaran langsung/onsite di klinik dan membawa identitas asli KTP/SIM. Dokter melakukan pemeriksaan standar. Pemeriksaan standar meliputi tinggi badan, berat badan, tes buta warna, dan pemeriksaan cacat badan. Biaya dasar pada template klinik Rp30.000 dan tidak ditanggung BPJS; pemeriksaan tambahan dapat menambah biaya. Tidak menerima pendaftaran online untuk layanan ini.",
                'keywords' => 'surat keterangan sehat sks biaya 30000 tes buta warna tinggi badan berat badan ktp sim onsite',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 90, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Surat Keterangan Sakit',
                'category' => 'Surat Keterangan',
                'content' => "Surat Keterangan Sakit hanya dapat dipertimbangkan setelah pasien diperiksa langsung oleh dokter. Dokter akan menilai kondisi pasien dan menerbitkan surat jika berdasarkan hasil pemeriksaan memang dibutuhkan istirahat.",
                'keywords' => 'surat sakit izin sakit sks sakit pemeriksaan langsung dokter',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 100, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Rujukan Baru / Pertama Kali',
                'category' => 'Rujukan',
                'content' => "Untuk rujukan pertama kali/baru, pasien perlu datang ke klinik untuk diperiksa oleh dokter. Keputusan dirujuk atau tidak ditentukan sesuai indikasi medis berdasarkan hasil pemeriksaan dokter.",
                'keywords' => 'rujukan baru pertama kali rumah sakit indikasi medis periksa dokter',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 110, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Pengambilan Surat Rujukan',
                'category' => 'Rujukan',
                'content' => "Surat rujukan dicetak saat pengambilan di bagian pendaftaran. Informasi template klinik menyebut rujukan dapat diambil maksimal 2x24 jam di bagian pendaftaran dengan membawa kartu BPJS dan surat rujuk balik dari rumah sakit, selain hari Minggu dan tanggal merah.",
                'keywords' => 'ambil rujukan surat rujukan 2x24 bpjs srb rumah sakit pendaftaran',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 120, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Pengambilan Obat setelah Konsultasi Online',
                'category' => 'Farmasi',
                'content' => "Setelah konsultasi online selesai, obat dapat diambil di Farmasi Klinik Pratama Mitra Sehat dengan membawa kartu BPJS. Pengambilan obat dilayani pada jam kerja, dengan waktu istirahat 13.00–14.00 WIB dan 17.30–19.00 WIB. Maksimal pengambilan obat 2x24 jam setelah pemeriksaan. Obat puyer dibuat saat pengambilan sehingga pasien diminta menunggu. Jika obat habis tetapi keluhan belum membaik, pasien disarankan kontrol kembali.",
                'keywords' => 'ambil obat farmasi bpjs puyer 2x24 konsultasi online jam istirahat obat habis kontrol',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 130, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Obat PRB',
                'category' => 'Farmasi',
                'content' => "Pengambilan obat resep rutin/PRB dilakukan di Farmasi Mitra Sehat. Pasien akan dihubungi oleh petugas farmasi melalui nomor yang digunakan klinik ketika obat sudah selesai disiapkan.",
                'keywords' => 'obat prb resep rutin farmasi mitra sehat pengambilan obat',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 140, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Skrining Riwayat Kesehatan BPJS',
                'category' => 'BPJS',
                'content' => "Peserta BPJS Kesehatan diminta mengisi Skrining Riwayat Kesehatan sebelum melanjutkan konsultasi online. Setelah mengisi, pasien diminta mengirimkan screenshot hasil skrining sebagai bukti dan mengonfirmasi melalui WhatsApp. Skrining cukup diisi satu tahun sekali sesuai kondisi kesehatan pasien.",
                'keywords' => 'skrining riwayat kesehatan bpjs konsultasi online screenshot satu tahun sekali',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 150, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Pemeriksaan Laboratorium',
                'category' => 'Laboratorium',
                'content' => "Klinik Mitra Sehat tidak memiliki fasilitas laboratorium sendiri. Pemeriksaan laboratorium dilakukan bekerja sama dengan pihak ketiga. Jika faskes pasien berada di Klinik Mitra Sehat, pasien diperiksa dokter terlebih dahulu dan dokter akan memberikan surat pengantar ke laboratorium mitra bila pemeriksaan laboratorium diperlukan.",
                'keywords' => 'laboratorium lab tes darah cek darah pihak ketiga surat pengantar lab',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 160, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Jadwal Umum Pelayanan Klinik',
                'category' => 'Jadwal Pelayanan',
                'content' => "Informasi pelayanan pada template klinik: Poli Umum Senin–Sabtu 24 jam dengan shift pagi 07.30–13.00, shift siang 14.30–21.00, dan shift malam 21.00–07.00; Minggu 07.00–21.00. Poli Gigi Senin–Jumat 08.00–12.00 dan Sabtu 13.00–17.00; tanggal merah libur. Poli KIA Senin–Sabtu, shift pagi 07.00–13.00 dan shift siang 14.00–21.00. Pelayanan online Senin–Minggu 08.00–20.00. Istirahat pelayanan shift pagi 13.00–14.00 dan shift siang 18.00–19.00.",
                'keywords' => 'jam buka jadwal poli umum 24 jam kia pelayanan online shift pagi siang malam minggu',
                'source' => 'template_whatsapp', 'is_active' => true, 'sort_order' => 170, 'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_knowledge');
    }
};
