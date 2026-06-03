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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
        $table->string('title'); // Service title
        $table->string('image')->nullable(); // Image path
        $table->text('description')->nullable(); // Brief description
        $table->longText('components')->nullable(); // GrapesJS components (HTML content)
        $table->longText('styles')->nullable(); // GrapesJS styles (CSS content)
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
