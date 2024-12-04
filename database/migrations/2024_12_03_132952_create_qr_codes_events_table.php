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
        Schema::create('qr_codes_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('qr_code');
            $table->string('image_url');
            $table->foreignUuid('event_id')->nullable()->constrained('events')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes_events');
    }
};
