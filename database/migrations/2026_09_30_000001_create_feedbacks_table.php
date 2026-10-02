<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 190);
            $table->enum('type', ['kritik', 'saran', 'apresiasi', 'lainnya'])->default('saran');
            $table->string('subject', 180)->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'read', 'replied'])->default('new')->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'created_at']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
