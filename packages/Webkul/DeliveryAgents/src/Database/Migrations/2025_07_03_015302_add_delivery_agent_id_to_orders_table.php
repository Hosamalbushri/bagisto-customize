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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('delivery_agent_id')->nullable();
            $table->foreign('delivery_agent_id')
                ->references('id')
                ->on('delivery_agents')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_agent_id']);
            $table->dropColumn('delivery_agent_id');
        });
    }
};
