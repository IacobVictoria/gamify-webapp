<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_quiz_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('quiz_id')->constrained('user_quizzes')->onDelete('cascade');
            $table->foreignUuid('question_id')->constrained('user_quiz_questions')->onDelete('cascade');
            $table->foreignUuid('answer_id')->constrained('user_quiz_answers')->onDelete('cascade');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_quiz_responses');
    }
};
