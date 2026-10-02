<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['information', 'important', 'urgent'])->default('information');
            $table->text('content');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index(['is_active', 'start_date', 'end_date']);
            $table->index(['is_pinned', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
