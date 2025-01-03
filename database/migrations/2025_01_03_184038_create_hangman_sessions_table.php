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
        Schema::create('hangman_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade'); // Jucătorul care a creat sesiunea
            $table->foreignId('opponent_id')->nullable()->constrained('users')->onDelete('cascade'); // Jucătorul invitat
            $table->string('word_for_creator')->nullable(); // Cuvânt atribuit creatorului
            $table->string('word_for_opponent')->nullable(); // Cuvânt atribuit oponentului
            $table->string('hint_for_creator')->nullable(); // Hint pentru creator
            $table->string('hint_for_opponent')->nullable(); // Hint pentru oponent
            $table->json('guessed_letters_creator')->nullable(); // Litere ghicite de creator
            $table->json('guessed_letters_opponent')->nullable(); // Litere ghicite de oponent
            $table->unsignedTinyInteger('mistakes_creator')->default(0); // Greșeli ale creatorului
            $table->unsignedTinyInteger('mistakes_opponent')->default(0); // Greșeli ale oponentului
            $table->unsignedBigInteger('turn'); // ID-ul jucătorului care este la rând
            $table->boolean('completed')->default(false); // Dacă sesiunea s-a terminat
            $table->json('scores')->nullable(); // Scorurile fiecărui jucător
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hangman_sessions');
    }
};
