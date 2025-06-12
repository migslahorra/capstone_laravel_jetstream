<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedProperty extends Model
{
    protected $fillable = ['user_id', 'property_id', 'notes'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
