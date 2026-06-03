<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $fillable = [
        'text_header_1',
        'description_1',
        'text_1',
        'points',
        'header_2',
        'description_2',
        'header_3',
        'description_3'
    ];

    protected $casts = [
        'points' => 'array',
    ];

    public function images()
    {
        return $this->hasMany(AboutUsImage::class);
    }
}
