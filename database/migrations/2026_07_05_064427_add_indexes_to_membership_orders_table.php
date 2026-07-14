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
        Schema::table('membership_orders', function (Blueprint $table) {

            // Mempercepat pencarian invoice pending
            $table->index(
                ['company_id', 'package', 'status'],
                'membership_order_lookup_idx'
            );

            // Mempercepat proses scheduler invoice expired
            $table->index(
                ['status', 'expires_at'],
                'membership_order_expired_idx'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membership_orders', function (Blueprint $table) {

            $table->dropIndex('membership_order_lookup_idx');

            $table->dropIndex('membership_order_expired_idx');

        });
    }
};