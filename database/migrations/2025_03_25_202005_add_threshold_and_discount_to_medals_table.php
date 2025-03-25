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
        Schema::table('medals', function (Blueprint $table) {
            $table->integer('threshold')->default(0)->after('tier'); // scor minim pentru medalie
            $table->integer('discount')->nullable()->after('threshold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medals', function (Blueprint $table) {
            $table->dropColumn(['threshold', 'discount']);
        });
    }
};
