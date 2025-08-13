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
            $table->unsignedInteger('state_area_id');

            $table->foreign('delivery_agent_id')
                ->references('id')->on('delivery_agents')
                ->onDelete('cascade');

            $table->foreign('state_area_id')
                ->references('id')->on('state_areas')
                ->onDelete('cascade');
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
