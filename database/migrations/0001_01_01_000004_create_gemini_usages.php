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
        Schema::create('gemini_usages', function (Blueprint $table) {

            $table->id();

            $table->string('model', 100);

            $table->string('supplier')->nullable();

            $table->unsignedInteger('prompt_tokens')->default(0);

            $table->unsignedInteger('output_tokens')->default(0);

            $table->unsignedInteger('total_tokens')->default(0);

            $table->unsignedInteger('elapsed_ms')->default(0);

            $table->unsignedSmallInteger('http_status')->nullable();

            $table->boolean('success')->default(true);

            $table->string('error_code', 30)->nullable();

            $table->text('error_message')->nullable();

            $table->string('company_phone', 30)->nullable();

            $table->string('invoice_number')->nullable();

            $table->decimal('invoice_total', 18, 2)->nullable();

            $table->unsignedInteger('image_size_kb')->default(0);

            $table->timestamps();

            $table->index('created_at');
            $table->index('model');
            $table->index('success');
            $table->index('http_status');
            $table->index('company_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gemini_usages');
    }
};