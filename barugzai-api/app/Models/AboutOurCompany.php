<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutOurCompany extends Model
{
    use HasFactory;


    protected $table = 'about_our_company';

    protected $fillable = ['value'];
}
