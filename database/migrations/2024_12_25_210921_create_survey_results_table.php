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
        Schema::create('survey_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('question_id')->references('id')->on('survey_questions')->cascadeOnDelete();
            $table->uuid('choice_id')->nullable(); // For multiple-choice questions
            $table->integer('scale')->nullable(); // For scale-based questions
            $table->text('answer')->nullable(); // For open-text questions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_results');
    }
};
