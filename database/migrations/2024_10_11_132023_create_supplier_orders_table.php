<?php

use App\Enums\OrderStatus;
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
        Schema::create('supplier_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('supplier_id')->constrained('suppliers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('order_date');
            $table->enum('status', array_column(OrderStatus::cases(), 'value'));
            $table->decimal('total_price');
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company_name');
            $table->string('address');
            $table->string('apartment')->nullable();
            $table->string('state');
            $table->string('city');
            $table->string('country');
            $table->string('zip_code');
            $table->string('phone');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_orders');
    }
};
