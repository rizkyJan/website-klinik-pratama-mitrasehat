<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 30)->default('gmail');
            $table->string('sender_name', 120);
            $table->string('email', 190);
            $table->string('smtp_host', 190);
            $table->unsignedSmallInteger('smtp_port')->default(465);
            $table->enum('smtp_scheme', ['smtp', 'smtps'])->default('smtps');
            $table->string('smtp_username', 190);
            $table->text('smtp_password');
            $table->timestamp('connected_at')->nullable();
            $table->timestamp('last_tested_at')->nullable();
            $table->text('last_error')->nullable();
            $table->foreignId('configured_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
