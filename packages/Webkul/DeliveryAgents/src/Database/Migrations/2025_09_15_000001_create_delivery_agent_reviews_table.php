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
        Schema::create('delivery_agent_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('delivery_agent_id');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('rating');
            $table->text('comment');
            $table->string('status');
            $table->timestamps();
            // Foreign key constraints
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('delivery_agent_id')->references('id')->on('delivery_agents')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            // Indexes for better performance
            $table->index(['delivery_agent_id', 'rating']);
            $table->index(['customer_id', 'created_at']);
            $table->index(['order_id']);
            $table->index(['rating', 'created_at']);
            // Ensure one review per order
            $table->unique('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_agent_reviews');
    }
};
