<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badge', 'badge_id', 'user_id');
    }
}
