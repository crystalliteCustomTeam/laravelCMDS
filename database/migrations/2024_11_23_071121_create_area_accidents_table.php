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
        Schema::create('area_accidents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('area_id')->nullable();
            $table->string('accident_code')->nullable();
            $table->text('description')->nullable();
            $table->string('severity_level')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->string('captured_image_url')->nullable();
            $table->string('reported_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_accidents');
    }
};
