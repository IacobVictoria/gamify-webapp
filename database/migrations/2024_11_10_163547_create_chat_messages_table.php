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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // Sender (user or admin)
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade'); // Receiver (user or admin)
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->enum('message_type', ['text', 'image', 'file', 'video'])->default('text');
            $table->string('attachment_url')->nullable();
            $table->foreignUuid('reply_to_message_id')->nullable()->constrained('chat_messages')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
