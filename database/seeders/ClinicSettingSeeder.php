<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClinicSetting;

class ClinicSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'clinic_name', 'value' => 'Klinik Pratama Mitra Sehat'],
            ['key' => 'clinic_tagline', 'value' => 'Mitra Tepat Menuju Sehat'],
            ['key' => 'clinic_address', 'value' => 'Jl. Veteran No.70, Ngabeyan, Jetis, Kec. Sukoharjo, Kabupaten Sukoharjo, Jawa Tengah 57511'],
            ['key' => 'clinic_whatsapp', 'value' => '0813-1170-9726'],
            ['key' => 'clinic_phone', 'value' => '0271-592374'],
            ['key' => 'clinic_tiktok', 'value' => '@klinikmitrasehat24'],
            ['key' => 'clinic_instagram', 'value' => '@klinikmitrasehat24'],
            ['key' => 'clinic_maps_url', 'value' => 'https://www.google.com/maps/search/?api=1&query=Klinik+Pratama+Mitra+Sehat+Sukoharjo'],
            ['key' => 'about_profile', 'value' => 'Klinik Pratama Mitra Sehat merupakan unit pelayanan kesehatan yang berada di bawah PT. Mitra Sehat Maju Sentosa (PT. MSMS).'],
            ['key' => 'about_history', 'value' => '2004-2012: Praktek dokter swasta (DPM)\n2012-2015: BP Mitra Sehat\n2015: Klinik Pratama Mitra Sehat (CV. Mitra Sehat)\n2021: PT. Mitra Sehat Maju Sentosa'],
            ['key' => 'vision', 'value' => 'Menjadi fasilitas kesehatan yang dekat di hati masyarakat dan tepat menjadi mitra dalam upaya mencari kesembuhan dan menjaga kesehatan di wilayah Sukoharjo dan sekitarnya.'],
            ['key' => 'mission', 'value' => 'Meningkatkan manajemen sarana kesehatan.\nMengembangkan SDM dokter dan tenaga kesehatan.\nMengembangkan infrastruktur untuk peningkatan kualitas pelayanan.\nAktif dalam upaya kesehatan promotif dan preventif masyarakat.'],
        ];

        foreach ($settings as $setting) {
            ClinicSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}