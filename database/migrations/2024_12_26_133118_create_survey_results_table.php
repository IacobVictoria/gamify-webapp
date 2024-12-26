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
        Schema::create('survey_results', function (Blueprint $table) {
           
                $table->uuid('id')->primary(); // ID unic pentru fiecare înregistrare
                $table->foreignUuid('survey_id')->references('id')->on('surveys')->cascadeOnDelete(); // Legătura cu survey-ul  $table->foreignUuid('user_id')->references('id')->on('users')->cascadeOnDelete(); // Legătura cu utilizatorul
                $table->json('responses'); // Câmp JSON care ține toate întrebările și răspunsurile
                $table->timestamps(); // Timestamps pentru created_at și updated_at
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
