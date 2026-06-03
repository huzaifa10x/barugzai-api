<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;


    protected $fillable = [
        'manufacturer_id',
        'car_model_id',
        'year',
        'mileage',
        'engine_size',
        'regional_spec',
        'warranty',
        'service_contact',
        'fuel_type',
        'description',
        'price',
        'instagram_link',
        'images',
        'sold',
        'views_count',
        'vehicle_type', 
        "seller_id"
    ];

    protected $casts = [
        'images' => 'array',
        'sold' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function images()
{
    return $this->morphMany(Image::class, 'imageable');
}

public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
