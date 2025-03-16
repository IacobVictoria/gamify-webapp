<?php

use App\Enums\TransactionType;
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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('transaction_type', array_column(TransactionType::cases(), 'value'));
            $table->foreignUuid('supplier_order_id')->nullable()->constrained('supplier_orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('client_order_id')->nullable()->constrained('client_orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('quantity');
            $table->dateTime('transaction_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
