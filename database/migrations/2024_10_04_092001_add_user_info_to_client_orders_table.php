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
        Schema::table('client_orders', function (Blueprint $table) {
            $table->string('email')->after('status');
            $table->string('first_name')->after('email');
            $table->string('last_name')->after('first_name');
            $table->string('address')->after('last_name');
            $table->string('apartment')->nullable()->after('address');
            $table->string('state')->after('apartment');
            $table->string('city')->after('state');
            $table->string('country')->after('city');
            $table->string('zip_code')->after('country');
            $table->string('phone')->after('zip_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_orders', function (Blueprint $table) {
            $table->dropColumn(['email', 'first_name', 'last_name', 'address', 'apartment', 'state', 'city', 'country', 'zip_code', 'phone']);
        });
    }
};
