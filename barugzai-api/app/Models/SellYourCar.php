<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellYourCar extends Model
{
    use HasFactory;
    protected $fillable = [
        'manufacturer',
        'model',
        'model_year',
        'chassis_no',
        'kilometers',
        'engine_size',
        'vehicle_options',
        'expected_price',
        'description',
        'full_name',
        'mobile_number',
        'email',
    ];
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    

}
