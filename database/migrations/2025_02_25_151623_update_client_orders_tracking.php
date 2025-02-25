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
            $table->timestamp('placed_at')->nullable()->after('status');
            $table->timestamp('expedited_at')->nullable()->after('placed_at');
            $table->timestamp('delivered_at')->nullable()->after('expedited_at');
            $table->timestamp('archived_at')->nullable()->after('delivered_at');
            $table->boolean('is_archived')->default(false)->after('archived_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_orders', function (Blueprint $table) {
            $table->dropColumn(['placed_at', 'expedited_at', 'delivered_at', 'archived_at', 'is_archived']);
        });
    }
};
