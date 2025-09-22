<?php
namespace Webkul\AdminTheme\Database\Migrations;

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
        Schema::create('state_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('country_state_id');
            $table->string('area_name')->nullable();
            $table->string('state_code')->nullable();
            $table->string('country_code')->nullable();
            $table->foreign('country_state_id')->references('id')->on('country_states')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('state_areas');

    }
};
