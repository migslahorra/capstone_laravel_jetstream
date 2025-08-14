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

    /**
     * Relationship: This property may be saved by many users.
     */
    public function savedByUsers()
    {
        return $this->hasMany(SavedProperty::class);
    }

    /**
     * Relationship: Owner of the property
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
