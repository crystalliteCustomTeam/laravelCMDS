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
        Schema::create('live_feeds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('area_id')->nullable(); // Reference to Area
            $table->string('device_id')->nullable();
            $table->string('live_feed_url')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->boolean('status')->default(1); // 1 for active, 0 for inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_feeds');
    }
};
