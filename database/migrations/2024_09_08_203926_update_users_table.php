<?php

use App\Enums\CityRomania;
use App\Enums\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new columns
            $table->enum('gender', array_column(Gender::cases(), 'value'))->nullable()->after('password');
            $table->integer('score')->default(0);
            $table->enum('location', array_column(CityRomania::cases(), 'value'))->nullable()->after('score');
            $table->date('birthdate')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gender', 'score', 'location', 'birthdate']);
        });
    }
};
