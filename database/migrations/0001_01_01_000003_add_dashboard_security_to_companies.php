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
        Schema::table('companies', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Dashboard Security
            |--------------------------------------------------------------------------
            */

            $table->string('access_token', 32)
                ->nullable()
                ->unique()
                ->after('phone');

            $table->string('dashboard_pin')
                ->nullable()
                ->after('access_token');

            $table->timestamp('pin_created_at')
                ->nullable()
                ->after('dashboard_pin');

            $table->unsignedTinyInteger('failed_attempts')
                ->default(0)
                ->after('pin_created_at');

            $table->timestamp('locked_until')
                ->nullable()
                ->after('failed_attempts');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {

            $table->dropColumn([
                'access_token',
                'dashboard_pin',
                'pin_created_at',
                'failed_attempts',
                'locked_until',
            ]);

        });
    }
};