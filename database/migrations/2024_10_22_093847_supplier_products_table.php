<?php

use App\Enums\ProductCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supplier_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->enum('category', array_column(ProductCategory::cases(), 'value'));
            $table->text('description')->nullable();
            $table->foreignUuid('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->integer('score');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->integer('calories')->nullable();      
            $table->decimal('protein', 5, 2)->nullable();   
            $table->decimal('carbs', 5, 2)->nullable();    
            $table->decimal('fats', 5, 2)->nullable(); 
            $table->decimal('fiber', 5, 2)->nullable();     
            $table->decimal('sugar', 5, 2)->nullable();            
            $table->text('ingredients')->nullable();        
            $table->text('allergens')->nullable();  
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
