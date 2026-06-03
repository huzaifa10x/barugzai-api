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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
        $table->foreignId('manufacturer_id')->constrained()->onDelete('cascade');
        $table->foreignId('car_model_id')->constrained()->onDelete('cascade');
        $table->integer('year');
        $table->integer('mileage');
        $table->string('engine_size');
        $table->string('regional_spec');
        $table->string('warranty')->nullable();
        $table->string('service_contact')->nullable();
        $table->string('fuel_type');
        $table->longText('description')->nullable();
        $table->decimal('price', 10, 2)->nullable();
        $table->string('instagram_link')->nullable();
        $table->json('images')->nullable();
        $table->boolean('sold')->default(false);
        $table->integer('views_count')->default(0);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
