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
        Schema::create('delivery_agent_ranges', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('delivery_agent_id');
            $table->string('area_name')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->foreign('delivery_agent_id')->references('id')->on('delivery_agents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_agent_ranges');
    }
};
