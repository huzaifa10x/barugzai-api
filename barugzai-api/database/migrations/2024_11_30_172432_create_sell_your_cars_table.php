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
        Schema::create('sell_your_cars', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer');
            $table->string('model');
            $table->string('model_year');
            $table->string('chassis_no');
            $table->string('kilometers');
            $table->string('engine_size');
            $table->string('vehicle_options')->nullable(); // Optional
            $table->string('expected_price')->nullable(); // Optional
            $table->text('description')->nullable(); // Optional
            $table->string('full_name');
            $table->string('mobile_number');
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_your_cars');
    }
};
