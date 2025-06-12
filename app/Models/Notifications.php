<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $fillable = ['user_id', 'type', 'title', 'message', 'is_read', 'notifiable_type', 'notifiable_id'];

    public function notifiable()
    {
        return $this->morphTo();
    }
}
