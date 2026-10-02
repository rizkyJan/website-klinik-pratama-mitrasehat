<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ClinicSettingSeeder::class,
            FacilitySeeder::class,
            ServiceSeeder::class,
            DoctorSeeder::class,
            FaqSeeder::class,
            HealthPackageSeeder::class,
            PromoSeeder::class,
            ArticleSeeder::class,
            GallerySeeder::class,
        ]);
    }
}