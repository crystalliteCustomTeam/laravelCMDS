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
        Schema::create('worksite', function (Blueprint $table) {
            $table->id();
            $table->string("Name");
            $table->string("Start_Date");
            $table->string("End_Date");
            $table->longText('Description');
            $table->bigInteger('CreateBy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worksite');
    }
};
