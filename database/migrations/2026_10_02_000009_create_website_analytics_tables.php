<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_visitors', function (Blueprint $table) {
            $table->id();
            $table->uuid('visitor_uuid')->unique();
            $table->timestamp('first_seen_at');
            $table->timestamp('last_seen_at');
            $table->unsignedBigInteger('total_page_views')->default(0);
            $table->timestamps();

            $table->index('last_seen_at');
        });

        Schema::create('website_daily_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('website_visitors')->cascadeOnDelete();
            $table->date('visit_date');
            $table->timestamp('first_seen_at');
            $table->timestamp('last_seen_at');
            $table->unsignedInteger('page_views')->default(1);
            $table->timestamps();

            $table->unique(['visitor_id', 'visit_date']);
            $table->index('visit_date');
        });

        Schema::create('website_page_views', function (Blueprint $table) {
            $table->id();
            $table->date('visit_date');
            $table->string('path', 255);
            $table->string('page_name', 120);
            $table->unsignedBigInteger('views')->default(1);
            $table->timestamps();

            $table->unique(['visit_date', 'path']);
            $table->index(['visit_date', 'views']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_page_views');
        Schema::dropIfExists('website_daily_visits');
        Schema::dropIfExists('website_visitors');
    }
};
