<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;



    protected $fillable = [
        'new_arrival_title',
        'new_arrival_description',
        'explore_service_title',
        'explore_service_subtitle',
        'explore_service_description',
        'videos_title',
        'videos_description',
        'youtube_video_1',
        'youtube_video_2',
        'youtube_video_3',
        'youtube_video_4',
    ];
}
