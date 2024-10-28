<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_quiz_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignUuid('quiz_id')->constrained('user_quizzes')->onDelete('cascade'); 
            $table->timestamp('date'); // Data la care a fost completat quiz-ul
            $table->integer('total_score'); // Scorul total obținut
            $table->integer('attempt_number')->default(1); // Numărul încercării
            $table->timestamps();
            $table->unique(['user_id', 'quiz_id', 'attempt_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_quiz_results');
    }
};
