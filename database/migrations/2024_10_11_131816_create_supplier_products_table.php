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
        Schema::create('supplier_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('category');
            $table->text('description')->nullable();
            $table->foreignUuid('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->integer('score');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_products');
    }
};
