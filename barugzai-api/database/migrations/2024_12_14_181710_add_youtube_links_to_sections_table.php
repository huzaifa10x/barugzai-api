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
        Schema::table('sections', function (Blueprint $table) {
            $table->string('youtube_video_1')->nullable();
            $table->string('youtube_video_2')->nullable();
            $table->string('youtube_video_3')->nullable();
            $table->string('youtube_video_4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn(['youtube_video_1', 'youtube_video_2', 'youtube_video_3', 'youtube_video_4']);

        });
    }
};
