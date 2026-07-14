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
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relasi
            |--------------------------------------------------------------------------
            */

            $table->foreignId('membership_order_id')
                ->constrained('membership_orders')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Payment Gateway
            |--------------------------------------------------------------------------
            */

            $table->string('gateway', 30);

            // QRIS, VA, DANA, OVO, GOPAY, dll.
            $table->string('channel', 50)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Reference
            |--------------------------------------------------------------------------
            */

            $table->string('reference', 100)->nullable();

            $table->string('external_id', 100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Nominal
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 18, 2);

            $table->string('currency', 10)
                ->default('IDR');

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            |
            | PENDING
            | PAID
            | FAILED
            | EXPIRED
            | CANCELLED
            |
            */

            $table->string('status', 20)
                ->default('PENDING');

            /*
            |--------------------------------------------------------------------------
            | Response Gateway
            |--------------------------------------------------------------------------
            */

            $table->json('payload')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('gateway');

            $table->index('status');

            $table->index('external_id');

            $table->index('created_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};