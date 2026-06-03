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
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('text_header_1');
            $table->longText('description_1');
            $table->string('text_1')->nullable();
            $table->json('points')->nullable();
            $table->string('header_2')->nullable();
            $table->longText('description_2')->nullable();
            $table->string('header_3')->nullable();
            $table->longText('description_3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us');
    }
};
