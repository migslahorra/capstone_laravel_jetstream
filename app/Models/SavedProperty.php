<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedProperty extends Model
{
    protected $fillable = [
        'user_id',
        'property_id',
        'notes',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
