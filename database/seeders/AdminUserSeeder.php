<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Jalankan database seeder.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'name' => 'Admin Klinik',

                // Gunakan password admin yang memang kamu pakai sekarang.
                // Jangan ubah kalau password admin-mu sekarang sudah benar.
                'password' => Hash::make('admin12345'),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Jadikan sebagai admin
        |--------------------------------------------------------------------------
        |
        | Tidak melalui $fillable supaya field is_admin tetap terlindungi.
        |
        */

        $admin->is_admin = true;
        $admin->save();
    }
}
