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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            // New Arrival section
            $table->string('new_arrival_title')->nullable();
            $table->text('new_arrival_description')->nullable();
            // Explore Our Service section
            $table->string('explore_service_title')->nullable();
            $table->string('explore_service_subtitle')->nullable();
            $table->text('explore_service_description')->nullable();
            // Our Videos section
            $table->string('videos_title')->nullable();
            $table->text('videos_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
