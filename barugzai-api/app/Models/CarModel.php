<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = ['manufacturer_id', 'name', 'body_type', 'description'];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
