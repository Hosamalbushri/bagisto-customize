<?php

namespace Webkul\DeliveryAgents\Database\Migrations;

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
        Schema::create('delivery_agents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender')->length(50)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('password')->nullable();
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->string('token')->nullable();
            $table->string('device_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
            // Add indexes for better performance
            $table->index('status');
            $table->index('email');
            $table->index('phone');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_agents');
    }
};
