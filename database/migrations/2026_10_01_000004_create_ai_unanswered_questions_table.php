<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_unanswered_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->text('last_question')->nullable();
            $table->string('normalized_question', 190)->index();
            $table->unsignedInteger('occurrences')->default(1);
            $table->string('status', 20)->default('pending');
            $table->foreignId('knowledge_id')->nullable()->constrained('ai_knowledge')->nullOnDelete();
            $table->timestamp('first_asked_at')->nullable();
            $table->timestamp('last_asked_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'occurrences']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_unanswered_questions');
    }
};
