<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price_range',
        'area',
        'address',
        'street',
        'purok',
        'city',
        'province',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'status',
        'images'
    ];

    protected $casts = [
        'images' => 'array',
    ];
}
