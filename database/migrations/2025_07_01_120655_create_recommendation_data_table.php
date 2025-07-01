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
        Schema::create('recommendation_data', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedBigInteger('user_id');
            $table->uuid('product_id');

            $table->boolean('target')->default(false);
            $table->boolean('has_bought')->default(false);
            $table->boolean('has_wishlisted')->default(false);
            $table->boolean('has_reviewed')->default(false);

            $table->float('user_rating')->nullable();
            $table->string('gender', 10)->nullable();
            $table->float('score_in_app')->nullable();

            $table->float('price')->nullable();
            $table->integer('stock')->nullable();

            $table->float('calories')->nullable();
            $table->float('protein')->nullable();
            $table->float('carbs')->nullable();
            $table->float('fats')->nullable();
            $table->float('fiber')->nullable();
            $table->float('sugar')->nullable();

            // Categorii (boolean)
            $table->boolean('category_Detox')->default(false);
            $table->boolean('category_EnergyDrinks')->default(false);
            $table->boolean('category_HealthySnacks')->default(false);
            $table->boolean('category_OrganicFoods')->default(false);
            $table->boolean('category_Supplements')->default(false);
            $table->boolean('category_Vitamins')->default(false);
            $table->boolean('category_WeightLoss')->default(false);

            // Alergeni (boolean)
            $table->boolean('allergen_eggs')->default(false);
            $table->boolean('allergen_lactose')->default(false);
            $table->boolean('allergen_none')->default(false);
            $table->boolean('allergen_nuts')->default(false);
            $table->boolean('allergen_gluten')->default(false);
            $table->boolean('allergen_soy')->default(false);
            $table->boolean('allergen_fish')->default(false);

            // Clustere (float)
            $table->float('cluster_0')->nullable();
            $table->float('cluster_1')->nullable();
            $table->float('cluster_2')->nullable();
            $table->float('cluster_3')->nullable();

            $table->float('health_score')->nullable();

            $table->integer('total_units_sold')->nullable();
            $table->integer('wishlist_count')->nullable();

            $table->float('average_rating')->nullable();
            $table->integer('review_count')->nullable();
            $table->float('avg_likes_per_review')->nullable();

            $table->timestamps();

            // Indexuri recomandate
            $table->index('user_id');
            $table->index('product_id');
            $table->index('target');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_data');
    }
};
