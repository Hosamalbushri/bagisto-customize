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
        Schema::create('product_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('auto_generate_sku')->default(false);
            $table->string('default_product_type')->default('simple');
            $table->string('sku_prefix')->default('PRD');
            $table->integer('sku_length')->default(6);
            $table->boolean('auto_fill_required_fields')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_settings');
    }
};
