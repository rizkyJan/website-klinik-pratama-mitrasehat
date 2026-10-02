<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->timestamps();
        });

        $now = now();
        DB::table('ai_settings')->insert([
            ['key' => 'enabled', 'value' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'assistant_name', 'value' => 'Asisten Klinik Mitra Sehat', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'welcome_message', 'value' => 'Halo! Saya Asisten Klinik Mitra Sehat. Saya dapat membantu informasi layanan klinik dan informasi kesehatan umum. Ada yang bisa saya bantu?', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'out_of_scope_message', 'value' => 'Mohon maaf, pertanyaan tersebut berada di luar layanan Asisten Klinik Mitra Sehat. Saya hanya dapat membantu seputar Klinik Mitra Sehat dan informasi kesehatan umum.', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'unknown_message', 'value' => 'Mohon maaf, informasi tersebut belum tersedia pada sistem Asisten Klinik Mitra Sehat. Pertanyaan Anda sudah kami catat agar dapat dilengkapi oleh pihak klinik. Untuk memastikan, silakan menghubungi petugas Klinik Mitra Sehat.', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'allow_health', 'value' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'store_history', 'value' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'store_unanswered', 'value' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'max_history_messages', 'value' => '8', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
